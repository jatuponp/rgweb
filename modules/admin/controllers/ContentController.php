<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\AccessControl;
use yii\web\Controller;
use yii\web\VerbFilter;
use app\models\Content;

class ContentController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    public function actionIndex() {
        $model = new Content;
        if($model->load($_POST)){
            $model->search = $_POST['Content']['search'];
        }
        return $this->render('index', ['model' => $model]);
    }

    public function actionUpdate($id = null) {
        $model = new Content;
        if ($model->load($_POST)) {
            $id = $_POST['Content']['id'];
            if ($id) {
                $model = Content::find($id);
                $model->attributes = $_POST['Content'];
            }
            if ($model->save()) {
                return $this->redirect(array('index'));
            }else{
                print_r($model->getErrors());
                exit();
            }
        }

        if ($id) {
            $model = Content::find(['id' => $id]);
        }
        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    public function actionView($id) {
        $model = Content::find(['id' => $id]);
        return $this->render('view', [
                    'model' => $model,
        ]);
    }
    
    public function actionDelete($id){
        $model = Content::find($id);
        $model->delete();
        return $this->redirect(array('index'));
    }

}
