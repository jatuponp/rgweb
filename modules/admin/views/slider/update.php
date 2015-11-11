<?php

use yii\helpers\Html;
use kartik\widgets\FileInput;
use yii\bootstrap\ActiveForm;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$this->title = 'บริหารภาพสไลด์';
?>
<div class="gallery-index">
    <?php
    $form = ActiveForm::begin([
                'id' => 'gallery-form',
                'options' => ['class' => '', 'enctype' => 'multipart/form-data'],
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'labelOptions' => ['class' => 'control-label'],
                ],
    ]);
    ?>
    <div class="row">
        <div class="page-header">
            <i class="glyphicon glyphicon-picture page-header-icon"></i> <?= Html::encode($this->title) ?>
            <div class="form-group pull-right">
                <?= Html::submitButton('<i class="glyphicon glyphicon-ok"></i> บันทึก', ['class' => 'btn btn-danger']) ?>
                <?= Html::resetButton('<i class="glyphicon glyphicon-remove"></i> ยกเลิก', ['class' => 'btn']) ?>
            </div>
        </div>
    </div>
    <br/>
    <div class="dashboard_box" style="padding-top: 15px;">
        <div class="row">
            <div class="col-sm-8">
                <?= $form->field($model, 'cid')->dropDownList(app\models\TblSlidertype::makeDropDown(), ['style' => 'width:200px; max-width: 400px;']); ?>

                <?php
                echo $form->field($model, 'upload_files')->widget(FileInput::classname(), [
                    'options' => ['multiple' => false],
                    'pluginOptions' => [
                        'showUpload' => false,
                        'showPreview' => true,
                        'showCaption' => false,
                        'uploadClass' => 'btn btn-info',
                        'removeClass' => 'btn btn-danger',
                        'elCaptionText' => '#customCaption',
                        'initialPreview' => [
                            Html::img("$model->slider_Url", ['class' => 'file-preview-image', 'alt' => '', 'title' => '']),
                        ],
                    ]
                ]);
                echo "ขนาดภาพ Slide: 1,263*520 px<br/>ขนาดภาพ Event: 800*600px<br/><br/>";
                
                //echo $form->field($model, 'target')->input('checkbox', ['style' => 'width: 400px;']);
                echo $form->field($model, 'id', ['options' => ['class' => 'sr-only']])->hiddenInput();
                echo $form->field($model, 'langs', ['options' => ['class' => 'sr-only']])->hiddenInput();
                ?>
            </div>
            <div class="col-sm-4">
                <?= $form->field($model, 'title')->input('text'); ?>
                <?= $form->field($model, 'fulltexts')->textarea() ?>
                <?= $form->field($model, 'positions')->dropDownList(['left'=>'ซ้าย','center'=>'ตรงกลาง','right'=>'ขวา',]); ?>
                <?= $form->field($model, 'link_Url')->input('text', ['placeholder' => 'http://']); ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
