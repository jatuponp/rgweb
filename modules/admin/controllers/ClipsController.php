<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\Clips;

class ClipsController extends \yii\web\Controller {

    public function actionIndex($id=null, $action = null) {
        $model = new Clips();
        
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
        $model = new Clips();
        if ($model->load($_POST)) {
            $id = $_POST['Clips']['id'];
            if ($id) {
                $model = Clips::findOne($id);
                $model->attributes = $_POST['Clips'];
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
            $model = Clips::findOne($id);
        }
        return $this->render('update', [
                    'model' => $model,
        ]);
    }
    
    private function published($id = null, $page = null) {
        if ($id) {
            $model = Clips::findOne($id);
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
    
    private function order($id = null, $ordering = null, $direction = null) {
        if ($id != null || $ordering != null || $direction != null) {
            $sess = Yii::$app->session->get('sessSlider');
            if ($direction == "up") {
                $newSortOrder = $ordering - 1;
            } else if ($direction == "down") {
                $newSortOrder = $ordering + 1;
            }
            
            $parent = Clips::findOne($id);

            $where = array();
            $where[] = "ordering = '$newSortOrder'";

            $connection = Yii::$app->db;
            $sql = "SELECT id FROM tbl_clips "
                    . ( count($where) ? "\n WHERE " . implode(' AND ', $where) : '' )
            ;
            $command = $connection->createCommand($sql);
            $reader = $command->query();

            foreach ($reader as $row) {
                $otherId = $row["id"];
            }

            $where = array();
            $where[] = "id = '$id'";

            $sql = 'UPDATE tbl_clips SET ordering = "' . $newSortOrder . '" '
                    . ( count($where) ? "\n WHERE " . implode(' AND ', $where) : '' )
            ;
            $command = $connection->createCommand($sql);
            $command->execute();
            if ($reader->getRowCount() > 0) {
                $where = array();
                $where[] = "id = '$otherId'";
                $sql = 'UPDATE tbl_clips SET ordering = "' . $ordering . '"'
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
        $sql = "SELECT $k, ordering FROM tbl_clips "
                . ($where ? "\nWHERE $where" : '')
                . "\nORDER BY ordering ASC";
        $command = $connection->createCommand($sql);
        $reader = $command->queryAll();

        $i = 1;
        foreach ($reader as $row) {
            $sql = "UPDATE tbl_clips SET ordering=$i WHERE $k = " . $row[$k];
            $command = $connection->createCommand($sql);
            $command->execute();
            $i++;
        }
    }

}
