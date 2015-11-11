<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use miloschuman\highcharts\Highcharts;

$this->title = 'สถิติการเยื่ยมชมเว็บไซต์';
?>
<div class="row">
    <div class="page-header"><i class="glyphicon glyphicon-stats page-header-icon"></i> <?= Html::encode($this->title) ?></div>
</div>
<br/>
<div class="site-stat">
    <div class="dashboard_box">
        <br/>
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
            <?php
            echo Highcharts::widget([
                'options' => [
                    'chart' => [
                        'type' => 'column'
                    ],
                    'title' => [
                        'text' => 'สถิติประจำปี ' . ($model->year + 543)
                    ],
                    'credits' => ['enabled' => false],
                    'exporting' => ['enabled' => false],
                    'xAxis' => [
                        'categories' => ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'],
                    ],
                    'yAxis' => [
                        'title' => [
                            'text' => 'จำนวนผู้เยื่ยมชม (ครั้ง)'
                        ],
                    ],
                    'tooltip' => [
                        'valueSuffix' => ' ครั้ง',
                    ],
                    'legend' => [
                        'layout' => 'vertical',
                        'align' => 'right',
                        'verticalAlign' => 'top',
                        'x' => -10,
                        'y' => 100,
                        'borderWidth' => 0
                    ],
                    'plotOptions' => [
                        'column' => [
                            'stacking' => 'normal',
                            'dataLabels' => [
                                'enabled' => true,
                                'color' => (Highcharts . theme && Highcharts . theme . dataLabelsColor) || 'white',
                                'style' => [
                                    'textShadow' => '0 0 3px black'
                                ]
                            ],
                            'point' => [
                                'events' => [
                                    'click' => new yii\web\JsExpression("function(){var url = 'day?month=' + (this.x+1) + '&year=" . $model->year . "';window.location.assign(url);}")
                                ]
                            ],
                        ],
                        'series' => [
                            'cursor' => 'pointer',
                        ]
                    ],
                    'series' => $model->searchYearBar(),
                ]
            ]);
            ?>
        </div>
    </div>
</div>