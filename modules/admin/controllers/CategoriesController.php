<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\Categories;

Yii::$app->name = '<i class="glyphicon glyphicon-folder-open"></i> บริหารหมวดหมู่บทความ';

class CategoriesController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['Editor']
                    ],
                ],
            ],
        ];
    }

    public function actionIndex() {
        $model = new Categories;
        if ($model->load(Yii::$app->request->post())) {
            $request = Yii::$app->request->post('Categories');
            $model->langs = $request['langs'];
        } else {
            $model->langs = Yii::$app->getRequest()->getQueryParam('langs', 'thai');
        }
        return $this->render('index', ['model' => $model]);
    }

    public function actionUpdate($id = null) {
        $model = new Categories;
        if ($model->load(Yii::$app->request->post())) {
            $request = Yii::$app->request->post('Categories');
            $id = $request['id'];
            if ($id) {
                $model = Categories::findOne($id);
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
            $model = Categories::findOne($id);
        } else {
            $model->langs = Yii::$app->getRequest()->getQueryParam('langs', 'thai');
        }
        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    public function actionDelete($id) {
        $model = Content::find($id);
        $model->delete();
        return $this->redirect(array('index'));
    }

}
