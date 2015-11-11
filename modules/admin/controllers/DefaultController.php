<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use app\models\Sitecounter;
use yii\filters\AccessControl;

class DefaultController extends Controller
{
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['Editor']
                    ],
                ],
            ],
        ];
    }
    
    public function actionIndex()
    {
        $model = new Sitecounter();
        if ($model->load($_POST)) {
            $sess = array();
            $sess['year'] = $_POST['Sitecounter']['year'];
            Yii::$app->session->set('sessStat', $sess);
        }
//
        $sess = Yii::$app->session->get('sessStat');
        $model->year = $sess['year'];
        if(!$model->year){
            $model->year = date('Y');
        }
        return $this->render('index', ['model' => $model]);
    }
}
