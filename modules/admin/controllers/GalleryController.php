<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\tblGallery;
use yii\base\ErrorException;
use yii\filters\AccessControl;

\Yii::$app->name = '<i class="glyphicon glyphicon-picture"></i> บริหารภาพกิจกรรม';

class GalleryController extends \yii\web\Controller {
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'update', 'published', 'delete', 'view', 'delimage'],
                        'allow' => true,
                        'roles' => ['Administrator']
                    ],
                ],
            ],
        ];
    }

    public function actionIndex() {
        $model = new tblGallery();
        if ($model->load(Yii::$app->request->post())) {
            $sess = array();
            $request = Yii::$app->request->post('tblGallery');
            $sess['search'] = $request['search'];
            $sess['langs'] = $request['langs'];
            $sess['amphur'] = $request['amphur'];
            Yii::$app->session->set('sesstblGallery', $sess);
        }

        $sess = Yii::$app->session->get('sesstblGallery');
        $model->search = $sess['search'];
        $model->langs = $sess['langs'];
        $model->amphur = $sess['amphur'];
        
        return $this->render('index', ['model' => $model]);
    }

    public function actionUpdate($id = null) {
        $model = new tblGallery;
        if ($model->load(Yii::$app->request->post())) {
            $request = Yii::$app->request->post('tblGallery');
            $id = $request['id'];
            if ($id) {
                $model = tblGallery::findOne($id);
                $model->attributes = $request;
            }
            if ($model->save()) {
                return $this->redirect(['index', 'langs' => $request['langs']]);
            } else {
                print_r($model->getErrors());
                exit();
            }
        }

        if ($id) {
            $model = tblGallery::findOne($id);
        } else {
            $model->langs = \Yii::$app->getRequest()->getQueryParam('langs');
        }
        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    public function actionDelete($id) {
        $model = tblGallery::findOne($id);
        if ($model->delete()) {
            $mDir = \Yii::getAlias('@webroot') . '/images/gallery/cat_' . $id;
            $this->deleteDir($mDir);
        }
        return $this->redirect(['index']);
    }

    public static function deleteDir($dirPath) {
        if (!is_dir($dirPath)) {
            throw new ErrorException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }

    public function actionView($id) {

        $model = tblGallery::findOne($id);
        if ($model->load($_POST)) {

            $files = \yii\web\UploadedFile::getInstances($model, 'upload_files');
            if (isset($files) && count($files) > 0) {
                $mPath = \Yii::getAlias('@webroot') . '/images/gallery/cat_' . $id;
                if (!is_dir($mPath)) {
                    mkdir($mPath);
                    chmod($mPath, '777');
                }
                foreach ($files as $file) {
                    $mPic = substr(number_format(time() * rand(), 0, '', ''), 0, 14) . '.' . $file->extension;
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
                            mkdir($mThumb);
                            chmod($mThumb, '777');
                        }
                        $image->save($mThumb . $mPic);
                    }
                }
            }
        }

        return $this->render('view', ['model' => $model]);
    }

    public function actionDelimage($id, $file) {

        $mPath = \Yii::getAlias('@webroot') . '/images/gallery/cat_' . $id . '/' . $file;
        $mThumb = \Yii::getAlias('@webroot') . '/images/gallery/cat_' . $id . '/thumb/' . $file;
        if (file_exists($mPath)) {
            @unlink($mPath);
            @unlink($mThumb);
        }

        return $this->redirect(['view', 'id' => $id]);
    }

}
