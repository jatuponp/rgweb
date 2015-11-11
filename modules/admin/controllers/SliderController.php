<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\Slider;

Yii::$app->name = '<i class="glyphicon glyphicon-picture"></i> บริหารภาพไสลด์';

class SliderController extends \yii\web\Controller {

    private function getPath() {
        return Yii::getAlias('@webroot') . '/images/slider/';
    }

    private function getUrl() {
        return Yii::getAlias('@web') . '/images/slider/';
    }

    public function actionIndex($id=null, $action = null) {
        $model = new Slider;
        if ($model->load($_POST)) {
            $sess = array();
            $sess['langs'] = $_POST['Slider']['langs'];
            $sess['cid'] = $_POST['Slider']['cid'];
            Yii::$app->session->set('sessSlider', $sess);
        }

        $sess = Yii::$app->session->get('sessSlider');
        $model->langs = $sess['langs'];
        if(!$model->langs){
            $model->langs = 'thai';
        }
        if(!$model->cid){
            $model->cid = 1;
        }else{
            $model->cid = $sess['cid'];
        }
        
        $type = new \app\models\TblSlidertype();
        if ($type->load(Yii::$app->request->post())) {
            if (!$type->save()) {
                throw new \ErrorException($model->getErrors());
            }
        }
        switch ($action) {
            case 'published':
                $this->actionPublished($id);
                break;
            case 'order':
                $this->actionOrder($id, $_REQUEST['ordering'], $_REQUEST['direction']);
                break;
            case 'typedelete':
                $this->typedelete($id);
                break;
        }
        return $this->render('index', ['model' => $model, 'type' => $type]);
    }
    
    public function actionPublished($id = null, $page = null) {
        if ($id) {
            $model = Slider::findOne($id);
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
        $model = Slider::findOne($id);
        if($model->delete()){            
            @unlink($model->slider_Url);
        }
        return $this->redirect(['index']);
    }
    
    private function typedelete($id) {
        $model = \app\models\TblSlidertype::findOne($id);
        $model->delete();
        //return $this->redirect(['index']);
    }
    
    public function actionUpdate($id = null) {
        $model = new Slider();
        if ($model->load($_POST)) {
            $id = $_POST['Slider']['id'];
            if ($id) {
                $model = Slider::findOne($id);
                $model->attributes = $_POST['Slider'];
            }
            $files = \yii\web\UploadedFile::getInstances($model, 'upload_files');
            if (isset($files) && count($files) > 0) {
                $mPath = $this->getPath();
                foreach ($files as $file) {
                    $mPic = 'nkc_' . substr(number_format(time() * rand(), 0, '', ''), 0, 14) . '.' . $file->extension;
                    //Upload Images
                    if ($file->saveAs($mPath . $mPic)) {
                        //$image = \Yii::$app->image->load($mPath . $mPic);
                        //$image->resize(950, 300);
                        //$image->save($mPath . '/' . $mPic);
                        $model->slider_Url = $this->getUrl() . $mPic;
                    }
                }
            }
            if ($model->save()) {
                $this->updateOrder("langs='" . $model->langs ."' AND cid='" . $model->cid . "' ");
                return $this->redirect(array('index'));
            } else {
                print_r($model->getErrors());
                exit();
            }
        }

        if ($id) {
            $model = Slider::findOne($id);
        } else {
            $sess = Yii::$app->session->get('sessSlider');
            $model->langs = $sess['langs'];
        }
        return $this->render('update', [
                    'model' => $model,
        ]);
    }
    
    public function actionOrder($id = null, $ordering = null, $direction = null) {
        if ($id != null || $ordering != null || $direction != null) {
            $sess = Yii::$app->session->get('sessSlider');
            if ($direction == "up") {
                $newSortOrder = $ordering - 1;
            } else if ($direction == "down") {
                $newSortOrder = $ordering + 1;
            }
            
            $parent = Slider::findOne($id);

            $where = array();
            $where[] = "ordering = '$newSortOrder'";
            $where[] = "langs = '" . $parent->langs . "'";

            $connection = Yii::$app->db;
            $sql = "SELECT id FROM tbl_slider "
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

            $sql = 'UPDATE tbl_slider SET ordering = "' . $newSortOrder . '" '
                    . ( count($where) ? "\n WHERE " . implode(' AND ', $where) : '' )
            ;
            $command = $connection->createCommand($sql);
            $command->execute();
            if ($reader->getRowCount() > 0) {
                $where = array();
                $where[] = "id = '$otherId'";
                $where[] = "langs = '" . $parent->langs . "'";
                $sql = 'UPDATE tbl_slider SET ordering = "' . $ordering . '"'
                        . ( count($where) ? "\n WHERE " . implode(' AND ', $where) : '' )
                ;
                $command = $connection->createCommand($sql);
                $command->execute();
            }

            return $this->redirect(['index','page'=>$_REQUEST['page']]);
        }
    }

    private function updateOrder($where = '') {
        $k = 'id';
        $connection = Yii::$app->db;
        $sql = "SELECT $k, ordering FROM tbl_slider "
                . ($where ? "\nWHERE $where" : '')
                . "\nORDER BY ordering ASC";
        $command = $connection->createCommand($sql);
        $reader = $command->queryAll();

        $i = 1;
        foreach ($reader as $row) {
            $sql = "UPDATE tbl_slider SET ordering=$i WHERE $k = " . $row[$k];
            $command = $connection->createCommand($sql);
            $command->execute();
            $i++;
        }
    }

}

?>