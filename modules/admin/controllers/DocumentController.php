<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\TblDocuments;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

Yii::$app->name = '<i class="glyphicon glyphicon-book"></i> บริหารแฟ้มเอกสาร';

class DocumentController extends \yii\web\Controller {

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['Editor']
                    ],
                    [
                        'actions' => ['published'],
                        'allow' => true,
                        'roles' => ['Publisher'],
                    ],
                    [
                        'actions' => ['delimage', 'order', 'import'],
                        'allow' => true,
                        'roles' => ['Administrator'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex($id = null, $action = null) {
        $model = new TblDocuments;
        if ($model->load($_POST)) {
            $sess = array();
            $sess['search'] = $_POST['TblDocuments']['search'];
            $sess['langs'] = $_POST['TblDocuments']['langs'];
            $sess['cid'] = $_POST['TblDocuments']['cid'];
            Yii::$app->session->set('sessTblDocuments', $sess);
        }

        $sess = Yii::$app->session->get('sessTblDocuments');
        $model->search = $sess['search'];
        //$model->langs = $sess['langs'];
        $model->cid = $sess['cid'];
        switch ($action){
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
        $model = new TblDocuments;
        if ($model->load($_POST)) {
            $id = $_POST['TblDocuments']['id'];
            if ($id) {
                $model = TblDocuments::findOne($id);
                $model->attributes = $_POST['TblDocuments'];
            }
            if ($model->save()) {
                $this->updateOrder('cid=' . $model->cid);

                $files = \yii\web\UploadedFile::getInstances($model, 'import');
                if (isset($files) && count($files) > 0) {
                    $mPath = \Yii::getAlias('@webroot') . '/files';
                    foreach ($files as $file) {
                        $mPic = date('M') . substr(number_format(time() * rand(), 0, '', ''), 0, 14) . '.' . $file->extension;
                        //Upload Images
                        if ($file->saveAs($mPath . '/' . $mPic)) {
                            $m = TblDocuments::findOne($model->id);
                            $m->file_name = $file;
                            $m->file_new = $mPic;
                            if (!$m->save()) {
                                print_r($m->getErrors());
                                exit();
                            }
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
            $model = TblDocuments::findOne($id);
        } else {
            $sess = Yii::$app->session->get('sessTblDocuments');
            $model->cid = $sess['cid'];
        }
        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    private function published($id = null, $page = null) {
        if ($id) {
            $model = TblDocuments::findOne($id);
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

    public function actionView($id) {
        $model = TblDocuments::find(['id' => $id]);
        return $this->render('view', [
                    'model' => $model,
        ]);
    }

    public function actionDelete($id) {
        $model = TblDocuments::findOne($id);
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

            $parent = TblDocuments::findOne($id);

            $where = array();
            $where[] = "ordering = '$newSortOrder'";
            $where[] = "cid = '" . $parent->cid . "'";

            $connection = Yii::$app->db;
            $sql = "SELECT id FROM tbl_documents "
                    . ( count($where) ? "\n WHERE " . implode(' AND ', $where) : '' )
            ;
            $command = $connection->createCommand($sql);
            $reader = $command->query();

            foreach ($reader as $row) {
                $otherId = $row["id"];
            }

            $where = array();
            $where[] = "id = '$id'";
            $where[] = "cid = '" . $parent->cid . "'";

            $sql = 'UPDATE tbl_documents SET ordering = "' . $newSortOrder . '" '
                    . ( count($where) ? "\n WHERE " . implode(' AND ', $where) : '' )
            ;
            $command = $connection->createCommand($sql);
            $command->execute();
            if ($reader->getRowCount() > 0) {
                $where = array();
                $where[] = "id = '$otherId'";
                $where[] = "cid = '" . $parent->cid . "'";
                $sql = 'UPDATE tbl_documents SET ordering = "' . $ordering . '"'
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
        $sql = "SELECT $k, ordering FROM tbl_documents "
                . ($where ? "\nWHERE $where" : '')
                . "\nORDER BY id DESC"; //"\nORDER BY id DESC"; //For Import  //ORDER BY ordering ASC For Normal
        $command = $connection->createCommand($sql);
        $reader = $command->queryAll();

        $i = 1;
        foreach ($reader as $row) {
            $sql = "UPDATE tbl_documents SET ordering=$i WHERE $k = " . $row[$k];
            $command = $connection->createCommand($sql);
            $command->execute();
            $i++;
        }
    }
    
    public function actionImport($cid) {
        $connection = Yii::$app->db2;
        switch ($cid) {
            case 17:
                //วาระงานผู้บริหาร
                $sql = "SELECT * FROM relate_file WHERE `type` = 'mnleft03'";
                break;
            case 18:
                //คำสั่ง/หนังสือสั่งการ/ประกาศ
                $sql = "SELECT * FROM relate_file WHERE `type` = 'mnleft06'";
                break;
            case 19:
                //บรรยายสรุปจังหวัดหนองคาย
                $sql = "SELECT * FROM relate_file WHERE `type` = 'mnleft07'";
                break;
            case 20:
                //แผนยุทธศาสตร์การพัฒนาจังหวัด
                $sql = "SELECT * FROM relate_file WHERE `type` = 'mnleft11'";
                break;
            case 21:
                //ผลการปฏิบัติราชการจังหวัด
                $sql = "SELECT * FROM relate_file WHERE `type` = 'mnleft08'";
                break;
            case 22:
                //คำรับรองการปฏิบัติราชการ
                $sql = "SELECT * FROM relate_file WHERE `type` = 'mnleft09'";
                break;
            case 23:
                //หมายเลขโทรศัพท์ที่สำคัญ
                $sql = "SELECT * FROM relate_file WHERE `type` = 'mnleft04'";
                break;
            case 24:
                //ข้อสั่งการประชุมกระทรวงมหาไทย
                $sql = "SELECT * FROM relate_file WHERE `type` = 'mnleft15'";
                break;
            case 25:
                //ดาวน์โหลด
                $sql = "SELECT * FROM relate_file WHERE `type` = 'mnleft05'";
                break;
            case 26:
                //ศูนย์ปฏิบัติการ AEC จังหวัดหนองคาย
                $sql = "SELECT * FROM relate_file WHERE `type` = 'aec'";
                break;
            case 27:
                //ศูนย์ดำรงธรรม
                $sql = "SELECT * FROM relate_file WHERE `type` = 'mnleft13'";
                break;
            case 28:
                //ศูนย์ข้อมูลข่าวสาร
                $sql = "SELECT * FROM relate_file WHERE `type` = 'data_center'";
                break;
            case 29:
                //ศูนย์ปฏิบัติการจังหวัด(POC)
                $sql = "SELECT * FROM relate_file WHERE `type` = 'poc'";
                break;
            case 30:
                //ศูนย์แก้ปัญหาและเอาชนะยาเสพติด
                $sql = "SELECT * FROM relate_file WHERE `type` = 'drug'";
                break;
            case 31:
                //ศูนย์ป้องกันและบรรเทาสาธารณภัย
                $sql = "SELECT * FROM relate_file WHERE `type` = 'def'";
                break;
            case 33:
                //ประชุมหัวหน้าส่วนราชการประจำจังหวัดหนองคาย
                $sql = "SELECT * FROM relate_file WHERE `type` = 'otop'";
                break;
            case 34:
                //รายงานผลการดำเนินงานตามนโยบายรัฐบาล จังหวัดหนองคาย
                $sql = "SELECT * FROM relate_file WHERE `type` = 'sgwm'";
                break;
            case 35:
                //	KM Knowledge Management จังหวัดหนองคาย
                $sql = "SELECT * FROM relate_file WHERE `type` = 'farm'";
                break;
            case 36:
                //การจัดหาระบบคอมพิวเตอร์
                $sql = "SELECT * FROM relate_file WHERE `type` = 'occ'";
                break;
            case 37:
                //การบริหารทรัพยากรบุคคล จังหวัดหนองคาย
                $sql = "SELECT * FROM relate_file WHERE `type` = 'mnleft10'";
                break;
            
        }
        $command = $connection->createCommand($sql);
        $reader = $command->queryAll();

        $i = 1;
        foreach ($reader as $row) {
            $model = new TblDocuments();
            $model->cid = $cid;
            $model->title = $row['name'];
            $model->file_name = $row['fname'];
            $model->file_new = $row['fname'];
            $model->file_update = date('Y-m-d h:i:s');
            $model->published = 1;
            $model->createby = 1;

            if (!$model->save()) {
                print_r($model->getErrors());
                exit();
            }
            $i++;
        }
        $this->updateOrder('cid=' . $cid);
        echo 'Sucessfull...' . $i . ' Record';
    }

}
