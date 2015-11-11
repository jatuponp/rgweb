<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\Article;
use yii\filters\AccessControl;

Yii::$app->name = '<i class="glyphicon glyphicon-book"></i> บริหารบทความ';

class ArticleController extends \yii\web\Controller {

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
        $model = new Article;
        if ($model->load($_POST)) {
            $sess = array();
            $sess['search'] = $_POST['Article']['search'];
            $sess['langs'] = $_POST['Article']['langs'];
            $sess['cid'] = $_POST['Article']['cid'];
            Yii::$app->session->set('sessArticle', $sess);
        }

        $sess = Yii::$app->session->get('sessArticle');
        $model->search = $sess['search'];
        $model->langs = $sess['langs'];
        $model->cid = $sess['cid'];
        
        switch ($action) {
            case 'published':
                $this->published($id);
                break;
            case 'order':
                $this->order($id, $_REQUEST['ordering'], $_REQUEST['direction']);
                break;
        }
        return $this->render('index', ['model' => $model]);
    }
    
    public function actionView($id){
        $model = Article::findOne($id);
        $this->redirect(['/site/view', 'id'=>$model->id]);
    }

    public function actionUpdate($id = null) {
        $model = new Article;
        if ($model->load(Yii::$app->request->post())) {
            $request = Yii::$app->request->post('Article');
            $id = $request['id'];
            if ($id) {
                $model = Article::findOne($id);
                $model->attributes = $request;
            }
            if ($model->save()) {
                $this->updateOrder('cid=' . $model->cid, '&langs=' . $model->langs);

                $files = \yii\web\UploadedFile::getInstances($model, 'upload_files');
                if (isset($files) && count($files) > 0) {
                    $mPath = \Yii::getAlias('@webroot') . '/images/article/news_' . $model->id;
                    if (!is_dir($mPath)) {
                        \yii\helpers\BaseFileHelper::createDirectory($mPath);
                    }
                    foreach ($files as $file) {
                        if($request['cid'] == '12'){
                            $mPic = $file->baseName . '.'. $file->extension;
                        }else{
                            $mPic = 'nkc_' . substr(number_format(time() * rand(), 0, '', ''), 0, 14) . '.' . $file->extension;
                        }
                        //Upload Images
                        if ($file->saveAs($mPath . '/' . $mPic)) {
                            $image = \Yii::$app->image->load($mPath . '/' . $mPic);
                            $image->resize(1024, 1024);
                            $image->save($mPath . '/' . $mPic);

                            //resize images thumb
                            $image = \Yii::$app->image->load($mPath . '/' . $mPic);
                            $image->resize(250, 250);
                            $mThumb = $mPath . '/thumb/';
                            if (!is_dir($mThumb)) {
                                \yii\helpers\BaseFileHelper::createDirectory($mThumb);
                            }
                            $image->save($mThumb . $mPic);
                        }
                    }
                }

                return $this->redirect(array('index'));
            } else {
                print_r($model->getErrors());
                exit();
            }
        }

        if ($id) {
            $model = Article::findOne($id);
        } else {
            $sess = Yii::$app->session->get('sessArticle');
            $model->langs = $sess['langs'];
            $model->cid = $sess['cid'];
        }
        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    private function published($id = null, $page = null) {
        if ($id) {
            $model = Article::findOne($id);
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

//    public function actionView($id) {
//        $model = Article::find(['id' => $id]);
//        return $this->render('view', [
//                    'model' => $model,
//        ]);
//    }

    public function actionDelete($id) {
        $model = Article::findOne($id);
        $model->delete();
        return $this->redirect(['index']);
    }

    public function actionDelimage($id, $file) {

        $mPath = \Yii::getAlias('@webroot') . '/images/article/news_' . $id . '/' . $file;
        $mThumb = \Yii::getAlias('@webroot') . '/images/article/news_' . $id . '/thumb/' . $file;
        if (file_exists($mPath)) {
            @unlink($mPath);
            @unlink($mThumb);
        }

        return $this->redirect(['update', 'id' => $id]);
    }

    private function order($id = null, $ordering = null, $direction = null) {
        if ($id != null || $ordering != null || $direction != null) {
            $sess = Yii::$app->session->get('sessArticle');
            if ($direction == "up") {
                $newSortOrder = $ordering - 1;
            } else if ($direction == "down") {
                $newSortOrder = $ordering + 1;
            }

            $parent = Article::findOne($id);

            $where = array();
            $where[] = "ordering = '$newSortOrder'";
            $where[] = "langs = '" . $parent->langs . "'";
            $where[] = "cid = '" . $parent->cid . "'";

            $connection = Yii::$app->db;
            $sql = "SELECT id FROM tbl_article "
                    . ( count($where) ? "\n WHERE " . implode(' AND ', $where) : '' )
            ;
            $command = $connection->createCommand($sql);
            $reader = $command->query();

            foreach ($reader as $row) {
                $otherId = $row["id"];
            }

            $where = array();
            $where[] = "id = '$id'";
            $where[] = "langs = '" . $parent->langs . "'";
            $where[] = "cid = '" . $parent->cid . "'";

            $sql = 'UPDATE tbl_article SET ordering = "' . $newSortOrder . '" '
                    . ( count($where) ? "\n WHERE " . implode(' AND ', $where) : '' )
            ;
            $command = $connection->createCommand($sql);
            $command->execute();
            if ($reader->getRowCount() > 0) {
                $where = array();
                $where[] = "id = '$otherId'";
                $where[] = "langs = '" . $parent->langs . "'";
                $where[] = "cid = '" . $parent->cid . "'";
                $sql = 'UPDATE tbl_article SET ordering = "' . $ordering . '"'
                        . ( count($where) ? "\n WHERE " . implode(' AND ', $where) : '' )
                ;
                $command = $connection->createCommand($sql);
                $command->execute();
            }

            return $this->redirect(['index', 'page' => $_REQUEST['page']]);
        }
    }

    private function updateOrder($where = '') {
        $k = 'id';
        $connection = Yii::$app->db;
        $sql = "SELECT $k, ordering FROM tbl_article "
                . ($where ? "\nWHERE $where" : '')
                . "\nORDER BY ordering ASC";
        $command = $connection->createCommand($sql);
        $reader = $command->queryAll();

        $i = 1;
        foreach ($reader as $row) {
            $sql = "UPDATE tbl_article SET ordering=$i WHERE $k = " . $row[$k];
            $command = $connection->createCommand($sql);
            $command->execute();
            $i++;
        }
    }

}
