<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use miloschuman\highcharts\Highcharts;
use kartik\tabs\TabsX;

$this->title = 'การวิเคราะห์เว็บไซต์วิทยาเขตหนองคาย';

$items = [
    [
        'label' => '<i class="glyphicon glyphicon-home"></i> แบ่งตามเบราว์เซอร์',
        'content' => $this->render('country', ['model' => $model]),
        'active' => true
    ],
    [
        'label' => '<i class="glyphicon glyphicon-user"></i> แบ่งตามระบบปฏิบัติการ',
        'linkOptions' => ['data-url' => \yii\helpers\Url::to(['stat/bycountry'])]
    ],
    [
        'label' => '<i class="glyphicon glyphicon-user"></i> แบ่งตามอุปกรณ์',
        'linkOptions' => ['data-url' => \yii\helpers\Url::to(['stat/bycountry'])]
    ],
    [
        'label' => '<i class="glyphicon glyphicon-user"></i> แบ่งตามประเทศ',
        'linkOptions' => ['data-url' => \yii\helpers\Url::to(['stat/bycountry'])]
    ],
];
?>
<div class="site-stat">
    <div class="page-header"><?= Html::encode($this->title) ?></div>
    <div class="row">
        <?php
        $form = ActiveForm::begin([
                    'id' => 'article-form',
                    'options' => [ 'class' => 'form-inline pull-right'],
                    'fieldConfig' => [
                        'template' => "{label}:&nbsp;&nbsp;{input}",
                    ],
        ]);
        ?>
        <?php
        echo $form->field($model, 'year')->dropDownList(['' => 'เลือกปี', '2014' => '2557', '2015' => '2558', '2016' => '2559', '2017' => '2560'], [ 'style' => 'margin-right: 10px; width: 250px;', 'onchange' => 'form.submit();']);
        ?>

        <?php ActiveForm::end(); ?>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?php
            echo TabsX::widget([
                'items' => $items,
                'position' => TabsX::POS_ABOVE,
                'encodeLabels' => false
            ]);
            ?>
        </div>        
    </div>
</div>