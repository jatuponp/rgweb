<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\Sitecounter;

class StatController extends \yii\web\Controller {

    public function actionIndex($id = null, $action = null) {
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
    
    public function actionDay($month,$year) {
        $model = new Sitecounter();        
        return $this->render('day', ['model' => $model,'month'=>$month, 'year'=>$year]);
    }

}
?>

