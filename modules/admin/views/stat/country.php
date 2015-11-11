<?php
    use miloschuman\highcharts\Highcharts;
    use kartik\social\GoogleAnalytics;
    
    echo GoogleAnalytics::widget(['id'=>'UA-62310130-1','domain'=>'auto']);
?>
<div class="row">
    <div class="col-sm-12">
    <?php
    echo Highcharts::widget([
        'options' => [
            'chart' => [
                'type' => 'column'
            ],
            'title' => [
                'text' => 'สถิติการเข้าชมเว็บไซต์ (แบ่งตามเบราว์เซอร์ที่ใช้) ประจำปี ' . ($model->year + 543)
            ],
            'credits' => ['enabled' => false],
            'exporting' => ['enabled' => false],
            'xAxis' => [
                'categories' => $model->searchBrowser(),
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
            'series' => $model->searchBrowserData(),
        ]
    ]);
    ?>
    </div>
</div>