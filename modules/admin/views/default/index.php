<?php

use yii\helpers\Html;
use miloschuman\highcharts\Highcharts;

$this->title = 'Dashboard';
?>
<div class="row">
    <div class="page-header"><?= Html::encode($this->title) ?></div>
</div>
<br/>
<div class="admin-default-index">

    <div class="col-md-12">
        <div class="row">
            <div class="col-md-8">
                <div class="row" style="padding-right: 7px;">
                    <div class="dashboard_box">
                        <h2>Statistic</h2>
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
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-12">
                        <div class="dashboard_box">
                            <h2>Country</h2>
                            <?php
                            $result = app\models\Sitecounter::find()
                                    ->select(['country', 'cnt' => 'count(*)'])
                                    ->where(['module' => 'site'])
                                    ->groupBy('country')
                                    ->orderBy('cnt DESC')
                                    ->limit(10)
                                    ->all();
                            echo '<table class="table table-striped"><tr><td width="70%">ประเทศ</td><td width="30%" style="text-align: right;">จำนวน</td></tr>';
                            foreach ($result as $r) {
                                echo "<tr><td>";
                                echo $r->country;
                                echo "</td><td style='text-align: right'>";
                                echo $r->cnt;
                                echo ' ครั้ง</td></tr>';
                            }
                            echo "</table>";
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

