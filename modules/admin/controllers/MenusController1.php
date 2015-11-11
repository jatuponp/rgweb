<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\Menus;

Yii::$app->name = '<i class="glyphicon glyphicon-tasks"></i> บริหารเมนูเว็บไซต์';

class MenusController extends \yii\web\Controller {

    public function actionIndex() {
        $model = new Menus;
        if ($model->load($_POST)) {
            $sess = array();
            $sess['langs'] = $_POST['Menus']['langs'];
            Yii::$app->session->set('sessMenus', $sess);
        }

        $sess = Yii::$app->session->get('sessMenus');
        $model->langs = $sess['langs'];
        return $this->render('index', ['model' => $model]);
    }

    public function actionUpdate($id = null) {
        $model = new Menus;
        if ($model->load($_POST)) {
            $id = $_POST['Menus']['id'];
            if ($id) {
                $model = Menus::findOne($id);
                $model->attributes = $_POST['Menus'];
            }

            if ($_POST['Menus']['content']) {
                $model->urls = 'index.php?r=content/view&id=' . $_POST['Menus']['content'];
            } else {
                $model->urls = $_POST['Menus']['urls'];
            }
            
            if ($model->save()) {
                $this->updateOrder('parent_id=' . $model->parent_id, '&langs=' . $model->langs);
                return $this->redirect(array('index'));
            } else {
                print_r($model->getErrors());
                exit();
            }
        }

        if ($id) {
            $model = Menus::findOne($id);
            list($a,$b,$c) = explode('=', $model->urls);
            $model->content = $c;
        } else {
            $sess = Yii::$app->session->get('sessMenus');
            $model->langs = $sess['langs'];
        }
        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    public function actionOrder($id = null, $ordering = null, $direction = null) {
        if ($id != null || $ordering != null || $direction != null) {
            $sess = Yii::$app->session->get('sessMenus');
            if ($direction == "up") {
                $newSortOrder = $ordering - 1;
            } else if ($direction == "down") {
                $newSortOrder = $ordering + 1;
            }

            $parent = Menus::find($id);

            $where = array();
            $where[] = "ordering = '$newSortOrder'";
            $where[] = "langs = '" . $sess['langs'] . "'";
            $where[] = "parent_id = '" . $parent->parent_id . "'";

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
            $where[] = "langs = '" . $sess['langs'] . "'";
            $where[] = "parent_id = '" . $parent->parent_id . "'";

            $sql = 'UPDATE tbl_menus SET ordering = "' . $newSortOrder . '" '
                    . ( count($where) ? "\n WHERE " . implode(' AND ', $where) : '' )
            ;
            $command = $connection->createCommand($sql);
            $command->execute();
            if ($reader->getRowCount() > 0) {
                $where = array();
                $where[] = "id = '$otherId'";
                $where[] = "langs = '" . $sess['langs'] . "'";
                $where[] = "parent_id = '" . $parent->parent_id . "'";
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

}
