<?php

namespace app\components;
use yii\base\Component;

class langs extends Component
{
    
    public function getLang(){
        $_l = ['th_TH' => 'thai', 'en_EN' => 'english', 'lo_LO' => 'lao', 'vi_VI' => 'vietnam'];
        $_language = \Yii::$app->language;
        return $_l[$_language];
    }
}

