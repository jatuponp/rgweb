<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\Newsletter;

class NewsletterController extends \yii\web\Controller {

    private function getPath() {
        return Yii::getAlias('@webroot') . '/images/newsletter/';
    }

    private function getUrl() {
        return Yii::getAlias('@web') . '/images/newsletter/';
    }

    public function actionIndex($id=null, $action = null) {
        $model = new Newsletter();
        
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

    public function actionUpdate($id = null) {
        $model = new Newsletter();
        if ($model->load($_POST)) {
            $id = $_POST['Newsletter']['id'];
            if ($id) {
                $model = Newsletter::findOne($id);
                $model->attributes = $_POST['Newsletter'];
            }

            //Upload Images
            $img = \yii\web\UploadedFile::getInstance($model, 'images');

            if (isset($img) && count($img) > 0) {
                $mPath = $this->getPath();
                $mPic = 'nkc_' . substr(number_format(time() * rand(), 0, '', ''), 0, 14) . '.' . $img->extension;
                //Upload Images
                if ($img->saveAs($mPath . $mPic)) {
                    $image = \Yii::$app->image->load($mPath . $mPic);
                    $image->resize(150, 248);
                    $image->save($mPath . '/' . $mPic);
                    $model->images = $this->getUrl() . $mPic;
                }
            }

            //Upload Documents
            $file = \yii\web\UploadedFile::getInstance($model, 'files');
            if (isset($file) && count($file) > 0) {
                $mPath = $this->getPath();
                //Upload Images
                if ($file->saveAs($mPath . $file)) {
                    $model->files = $this->getUrl() . $file;
                }
            }

            if ($model->save()) {
                $this->updateOrder();
                return $this->redirect(array('index'));
            } else {
                print_r($model->getErrors());
                exit();
            }
        }

        if ($id) {
            $model = Newsletter::findOne($id);
        }
        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    private function published($id = null, $page = null) {
        if ($id) {
            $model = Newsletter::findOne($id);
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
        $model = Newsletter::findOne($id);
        if ($model->delete()) {
            @unlink($model->images);
            @unlink($model->files);
        }
        return $this->redirect(['index']);
    }

    private function order($id = null, $ordering = null, $direction = null) {
        if ($id != null || $ordering != null || $direction != null) {
            $sess = Yii::$app->session->get('sessNewsletter');
            if ($direction == "up") {
                $newSortOrder = $ordering - 1;
            } else if ($direction == "down") {
                $newSortOrder = $ordering + 1;
            }

            $parent = Newsletter::findOne($id);

            $where = array();
            $where[] = "ordering = '$newSortOrder'";

            $connection = Yii::$app->db;
            $sql = "SELECT id FROM tbl_newsletter "
                    . ( count($where) ? "\n WHERE " . implode(' AND ', $where) : '' )
            ;
            $command = $connection->createCommand($sql);
            $reader = $command->query();

            foreach ($reader as $row) {
                $otherId = $row["id"];
            }

            $where = array();
            $where[] = "id = '$id'";

            $sql = 'UPDATE tbl_newsletter SET ordering = "' . $newSortOrder . '" '
                    . ( count($where) ? "\n WHERE " . implode(' AND ', $where) : '' )
            ;
            $command = $connection->createCommand($sql);
            $command->execute();
            if ($reader->getRowCount() > 0) {
                $where = array();
                $where[] = "id = '$otherId'";
                $sql = 'UPDATE tbl_newsletter SET ordering = "' . $ordering . '"'
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
        $sql = "SELECT $k, ordering FROM tbl_newsletter "
                . ($where ? "\nWHERE $where" : '')
                . "\nORDER BY ordering ASC";
        $command = $connection->createCommand($sql);
        $reader = $command->queryAll();

        $i = 1;
        foreach ($reader as $row) {
            $sql = "UPDATE tbl_newsletter SET ordering=$i WHERE $k = " . $row[$k];
            $command = $connection->createCommand($sql);
            $command->execute();
            $i++;
        }
    }

}
