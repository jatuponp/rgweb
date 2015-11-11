<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\Menus;
use app\models\TblMenutype;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Json;

Yii::$app->name = '<i class="glyphicon glyphicon-tasks"></i> บริหารเมนูเว็บไซต์';

class MenusController extends \yii\web\Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'update', 'published', 'delete', 'getsubmenu'],
                        'allow' => true,
                        'roles' => ['Editor']
                    ],
                ],
            ],
        ];
    }

    public function actionIndex($id = null, $action = null) {
        $model = new Menus();

        if ($model->load(Yii::$app->request->post())) {
            $request = Yii::$app->request->post('Menus');
            $sess = array();
            $sess['type'] = $request['type'];
            $sess['langs'] = $request['langs'];
            Yii::$app->session->set('sessMenus', $sess);
        }
        
        $sess = Yii::$app->session->get('sessMenus');
        $model->type = $sess['type'];
        $model->langs = (($sess['langs'])? $sess['langs']:'thai');

        $type = new TblMenutype();
        if ($type->load(Yii::$app->request->post())) {
            $type->langs = $model->langs;
            $type->gid = Yii::$app->user->identity->gid;
            if (!$type->save()) {
                throw new \ErrorException($model->getErrors());
            }
        }
        
        switch ($action) {
            case 'published':
                $this->published($id);
                break;
            case 'order':
                $this->order($id, Yii::$app->getRequest()->getQueryParam('ordering'), Yii::$app->getRequest()->getQueryParam('direction'));
                break;
            case 'typedelete':
                $this->typedelete($id);
                break;
        }
        return $this->render('index', ['model' => $model, 'type' => $type]);
    }
    
    public function actionView($id){
        $model = Menus::findOne($id);
        $this->redirect($model->urls . '&mid=' . $model->type);
    }

    public function actionUpdate($type = null, $id = null) {
        $model = new Menus;
        if ($model->load($_POST)) {
            $id = $_POST['Menus']['id'];
            if ($id) {
                $model = Menus::findOne($id);
                $model->attributes = $_POST['Menus'];
            }
            if ($type) {
                $model->links = $type;
            }

            $files = \yii\web\UploadedFile::getInstances($model, 'pics');

            if (isset($files) && count($files) > 0) {
                $mPath = \Yii::getAlias('@webroot') . '/images/icon';
                $mUrl = \Yii::getAlias('@web') . '/images/icon';
                foreach ($files as $file) {
                    $mFile = substr(number_format(time() * rand(), 0, '', ''), 0, 14) . '.' . $file->extension;
                    //Upload Images
                    if ($file->saveAs($mPath . '/' . $mFile)) {
                        $model->pics = $mUrl . '/' . $mFile;
                    }
                }
            }
            $model->parent_id = (($_POST['Menus']['parent_id']) ? $_POST['Menus']['parent_id'] : 0);
            if ($model->save()) {
                $this->updateOrder('parent_id=' . $model->parent_id . ' AND type=' . $model->type);
                return $this->redirect(array('index'));
            } else {
                print_r($model->getErrors());
                exit();
            }
        }

        if ($id) {
            $model = Menus::findOne($id);
            list($a, $b, $c) = explode('=', $model->urls);
            $model->content = $c;
            $type = $model->links;
        } else {
            $sess = Yii::$app->session->get('sessMenus');
            $model->langs = $sess['langs'];
        }
        return $this->render('update', [
                    'model' => $model,
                    'type' => $type,
        ]);
    }

    private function typedelete($id) {
        $model = TblMenutype::findOne($id);
        $model->delete();
        //return $this->redirect(['index']);
    }

    private function published($id) {
        if ($id) {
            $model = Menus::findOne($id);
            if ($model->published == 0) {
                $model->published = 1;
            } else {
                $model->published = 0;
            }
            if ($model->save()) {
                return $this->redirect(['index']);
            } else {
                print_r($model->getErrors());
                exit();
            }
        }
    }

    public function actionDelete($id) {
        $model = Menus::findOne($id);
        if ($model->delete()) {
            if (Yii::$app->getRequest()->isAjax) {
                $model = new Menus();
                return $this->renderPartial('index', [
                            'model' => $model
                ]);
            }
            return $this->redirect(['index']);
        }
    }

    private function order($id = null, $ordering = null, $direction = null) {
        if ($id != null || $ordering != null || $direction != null) {
            $sess = Yii::$app->session->get('sessMenus');
            if ($direction == "up") {
                $newSortOrder = $ordering - 1;
            } else if ($direction == "down") {
                $newSortOrder = $ordering + 1;
            }

            $parent = Menus::findOne($id);

            $where = array();
            $where[] = "ordering = '$newSortOrder'";
            //$where[] = "langs = '" . $sess['langs'] . "'";
            $where[] = "parent_id = '" . $parent->parent_id . "'";
            $where[] = "type = '" . $parent->type . "'";

            $connection = Yii::$app->db;
            $sql = "SELECT id FROM tbl_menus "
                    . ( count($where) ? "\n WHERE " . implode(' AND ', $where) : '' )
            ;
            $command = $connection->createCommand($sql);
            $reader = $command->query();

            foreach ($reader as $row) {
                $otherId = $row["id"];
            }

            $where = array();
            $where[] = "id = '$id'";
            //$where[] = "langs = '" . $sess['langs'] . "'";
            $where[] = "parent_id = '" . $parent->parent_id . "'";
            $where[] = "type = '" . $parent->type . "'";

            $sql = 'UPDATE tbl_menus SET ordering = "' . $newSortOrder . '" '
                    . ( count($where) ? "\n WHERE " . implode(' AND ', $where) : '' )
            ;
            $command = $connection->createCommand($sql);
            $command->execute();
            if ($reader->getRowCount() > 0) {
                $where = array();
                $where[] = "id = '$otherId'";
                //$where[] = "langs = '" . $sess['langs'] . "'";
                $where[] = "parent_id = '" . $parent->parent_id . "'";
                $where[] = "type = '" . $parent->type . "'";
                $sql = 'UPDATE tbl_menus SET ordering = "' . $ordering . '"'
                        . ( count($where) ? "\n WHERE " . implode(' AND ', $where) : '' )
                ;
                $command = $connection->createCommand($sql);
                $command->execute();
            }

            return $this->redirect(['index']);
        }
    }

    private function updateOrder($where = '') {
        $k = 'id';
        $connection = Yii::$app->db;
        $sql = "SELECT $k, ordering FROM tbl_menus "
                . ($where ? "\nWHERE $where" : '')
                . "\nORDER BY ordering ASC";
        $command = $connection->createCommand($sql);
        $reader = $command->queryAll();

        $i = 1;
        foreach ($reader as $row) {
            $sql = "UPDATE tbl_menus SET ordering=$i WHERE $k = " . $row[$k];
            $command = $connection->createCommand($sql);
            $command->execute();
            $i++;
        }
    }

    public function actionGetsubmenu() {
        global $arr;
        $arr = array();
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];

            if ($parents != null) {
                $type = $parents[0];

                $menus = Menus::find()
                        ->where(['type' => $type, 'parent_id' => 0])
                        ->all();
                foreach ($menus as $m) {
                    $data = array();
                    $data['id'] = $m->id;
                    $data['name'] = $m->names;
                    $arr[] = $data;
                    $this->listMenusSub($m->id);
                }

                echo Json::encode(['output' => $arr, 'selected' => $selected]);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected' => '']);
    }

    public function listMenusSub($parent, $space = '|---') {
        global $arr;

        $children = Menus::find()
                ->where(['parent_id' => $parent])
                ->orderBy('ordering')
                ->all();
        foreach ($children as $child) {
            $data = array();
            $data['id'] = $child->id;
            $data['name'] = $space . ' ' . $child->names;
            $arr[] = $data;
            $this->listMenusSub($child->id, $space . ' ---');
        }
    }

}
