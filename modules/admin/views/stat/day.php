<?php

use yii\helpers\Html;
use miloschuman\highcharts\Highcharts;

$this->title = 'สถิติการเยื่ยมชมเว็บไซต์';
?>
<div class="row">
    <div class="page-header"><i class="glyphicon glyphicon-stats page-header-icon"></i> <?= Html::encode($this->title) ?></div>
</div>
<br/>
<div class="site-stat">
    <div class="dashboard_box">
        <div class="row">
            <?php
            echo Highcharts::widget([
                'options' => [
                    'chart' => [
                        'type' => 'column'
                    ],
                    'title' => [
                        'text' => 'สถิติประจำเดือน ' . $month . ' ปี ' . ($year + 543)
                    ],
                    'credits' => ['enabled' => false],
                    'exporting' => ['enabled' => false],
                    'xAxis' => [
                        'categories' => ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31'],
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
                            ]
                        ],
                        'series' => [
                            'cursor' => 'pointer',
                        ]
                    ],
                    'series' => $model->searchDayBar($month, $year),
                ]
            ]);
            ?>
        </div>
    </div>
</div>