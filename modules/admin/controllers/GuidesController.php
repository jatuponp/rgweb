<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\TblGuides;
use yii\filters\AccessControl;

Yii::$app->name = '<i class="glyphicon glyphicon-book"></i> ข้อมูลการท่องเที่ยว';

class GuidesController extends \yii\web\Controller {    

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['Editor']
                    ],
                    [
                        'actions' => ['published'],
                        'allow' => true,
                        'roles' => ['Publisher'],
                    ],
                    [
                        'actions' => ['delimage', 'order'],
                        'allow' => true,
                        'roles' => ['Administrator'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex($id = null, $action = null) {
        $cid = Yii::$app->getRequest()->getQueryParam('cid');
        $model = new TblGuides;
        if ($model->load($_POST)) {
            $sess = array();
            $sess['search'] = $_POST['TblGuides']['search'];
            $sess['langs'] = $_POST['TblGuides']['langs'];
            $sess['cid'] = $_POST['TblGuides']['cid'];
            $sess['amphur'] = $_POST['TblGuides']['amphur'];
            Yii::$app->session->set('sessTblGuides', $sess);
        }

        $sess = Yii::$app->session->get('sessTblGuides');
        $model->search = $sess['search'];
        $model->langs = $sess['langs'];
        $model->cid = (($cid)? $cid : $sess['cid']);
        //$model->amphur = $sess['amphur'];

        switch ($action) {
            case 'published':
                $this->published($id);
                break;
        }
        return $this->render('index', ['model' => $model]);
    }

    public function actionUpdate($id = null) {
        $cid = Yii::$app->getRequest()->getQueryParam('cid');
        $model = new TblGuides;
        if ($model->load($_POST)) {
            $id = $_POST['TblGuides']['id'];
            if ($id) {
                $model = TblGuides::findOne($id);
                $model->attributes = $_POST['TblGuides'];
            }
            if ($model->save()) {
                return $this->redirect(array('index', 'cid' => $model->cid));
            } else {
                print_r($model->getErrors());
                exit();
            }
        }

        if ($id) {
            $model = TblGuides::findOne($id);
        } else {
            $sess = Yii::$app->session->get('sessTblGuides');
            $model->langs = $sess['langs'];
            $model->cid = (($cid)? $cid : $sess['cid']);
        }
        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    private function published($id = null, $page = null) {
        if ($id) {
            $model = TblGuides::findOne($id);
            if ($model->published == 0) {
                $model->published = 1;
            } else {
                $model->published = 0;
            }
            if ($model->save()) {
                return $this->redirect(['index', 'page' => $page]);
            } else {
                print_r($model->getErrors());
                exit();
            }
        } else {
            return $this->redirect(['index', 'page' => $page]);
        }
    }
    
    public function actionDelete($id) {
        $model = TblGuides::findOne($id);
        $model->delete();
        return $this->redirect(['index', 'cid' => $model->cid]);
    }

}
