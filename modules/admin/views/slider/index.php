<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use \yii\helpers\Url;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\LoginForm $model
 */
$this->title = 'บริหารภาพไสลด์';
?>
<div class="row">
    <div class="page-header"><i class="glyphicon glyphicon-picture page-header-icon"></i> <?= Html::encode($this->title) ?></div>
</div>
<br/>
<div class="slider-index">   
    <div class="dashboard_box">
        <br/>
        <div class="row">
            <div class="col-lg-4">
                <a href="<?= Url::to(['slider/update']) ?>" class="btn btn-danger"><i class="glyphicon glyphicon-plus"></i> เพิ่มภาพสไลด์</a>
            </div>
            <div class="col-lg-8">
                <?php
                Modal::begin([
                    'options' => ['id' => 'new'],
                    'header' => '<h4 style="margin:0; padding:0">ประเภทภาพสไลด์ </h4>',
                    'toggleButton' => ['label' => '<i class="glyphicon glyphicon-th-large"></i>', 'class' => 'btn btn-danger pull-right'],
                ]);
                Pjax::begin();
                $form = ActiveForm::begin([
                            'id' => 'article-form',
                            'options' => ['data-pjax' => true],
                            'fieldConfig' => [
                                'template' => "{label}<div class=\"input-group\">{input}<span class=\"input-group-btn\"><button class=\"btn btn-primary\" type=\"submit\">เพิ่ม</button></span></div>{hint}{error}",
                            //'labelOptions' => [ 'class' => 'sr-only'],
                            ],
                ]);
                echo GridView::widget([
                    'dataProvider' => $type->search(),
                    'layout' => '{items}',
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'headerOptions' => ['width' => '5%'],
                        ],
                        [
                            'headerOptions' => ['width' => '88%'],
                            'header' => 'ประเภทสไลด์',
                            'attribute' => 'title'
                        ],
                        [
                            'headerOptions' => ['width' => '7%', 'style' => 'text-align:center;'],
                            'contentOptions' => [ 'align' => 'center'],
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{delete}',
                            'buttons' => [
                                'delete' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', Url::to(['index', 'action' => 'typedelete', 'id' => $model->id]));
                                },
                                    ],
                                ],
                            ]
                        ]);
                        ?>
                        <div style="margin-top: 10px;">
                            <?= $form->field($type, 'title')->input('text', ['placeholder' => 'กรอกชื่อปะรเภท'])->hint('เช่น สไลด์ด้านบน, สไลด์ด้านซ้าย') ?>
                        </div>
                        <div class="modal-footer">
                            <a href="<?= Url::to(['index']) ?>" data-dismiss="modal" class="btn btn-default">ปิด</a>
                        </div>

                        <?php
                        ActiveForm::end();
                        Pjax::end();
                        Modal::end();
                        ?>
                        <?php
                        $form = ActiveForm::begin([
                                    'id' => 'menus-form',
                                    'options' => ['class' => 'form-inline pull-right'],
                                    'fieldConfig' => [
                                        'template' => "{label}{input}&nbsp;",
                                        'labelOptions' => ['class' => 'sr-only'],
                                    ],
                        ]);
                        ?>

                        <?php
                        echo $form->field($model, 'langs')->dropDownList(\app\models\tblLangs::makeDropDown(), ['style' => 'width: 150px;', 'onchange' => 'form.submit();']);
                        echo "&nbsp;";
                        echo $form->field($model, 'cid')->dropDownList(app\models\TblSlidertype::makeDropDown(), ['onchange' => 'form.submit();']);
                        ?>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div><br/>
                <?php
                Pjax::begin();
                echo GridView::widget([
                    'dataProvider' => $model->search(),
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'headerOptions' => ['width' => '5%'],
                        ],
                        [
                            'headerOptions' => ['width' => '60%'],
                            'header' => '',
                            'format' => 'raw',
                            'value' => function($model) {
                        return '<img src="' . $model->slider_Url . '" width="100%"/>';
                    }
                        ],
                        [
                            'headerOptions' => ['width' => '16%'],
                            'header' => 'การเชื่อมโยง',
                            'attribute' => 'link_Url',
                        ],
                        [
                            'headerOptions' => ['width' => '5%', 'style' => 'text-align:center;'],
                            'contentOptions' => [ 'align' => 'center'],
                            'class' => 'yii\grid\CMSColumn',
                            'template' => '{published}',
                            'header' => 'แสดง'
                        ],
                        [
                            'headerOptions' => ['width' => '7%', 'style' => 'text-align:center;'],
                            'contentOptions' => [ 'align' => 'center'],
                            'class' => 'yii\grid\CMSColumn',
                            'template' => '{up} {down}',
                            'header' => 'เรียง'
                        ],
                        [
                            'headerOptions' => ['width' => '7%', 'style' => 'text-align:center;'],
                            'contentOptions' => [ 'align' => 'center'],
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{update} {delete}',
                            'header' => 'แก้ไข'
                        ],
                    ]
                ]);
                Pjax::end();
                ?> 
    </div>
</div>
