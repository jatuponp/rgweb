<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\file\FileInput;
use yii\widgets\ActiveForm;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$this->title = 'จดหมายข่าว';
?>
<div class="gallery-index">
    <?php
    $form = ActiveForm::begin([
                'id' => 'gallery-form',
                'options' => ['class' => 'horizontal', 'enctype' => 'multipart/form-data'],
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'labelOptions' => ['class' => 'control-label'],
                ],
    ]);
    ?>
    <div class="page-header">
        <?= Html::encode($this->title) ?>
        <div class="form-group pull-right">
            <?= Html::submitButton('<i class="glyphicon glyphicon-ok"></i> บันทึก', ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton('<i class="glyphicon glyphicon-remove"></i> ยกเลิก', ['class' => 'btn']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-2">
            <?php echo $form->field($model, 'years')->input('text', ['style' => 'width: 100px;']) ?> 
        </div>
        <div class="col-sm-10">
            <?php echo $form->field($model, 'volumn')->input('text', ['style' => 'width: 100px;']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            ขนาดภาพที่แนะนำ 150px X 248px
            <?php
            echo $form->field($model, 'images')->widget(FileInput::classname(), [
                'options' => ['multiple' => false],
                'pluginOptions' => [
                    'showUpload' => false,
                    'showPreview' => true,
                    'showCaption' => false,
                    'uploadClass' => 'btn btn-info',
                    'removeClass' => 'btn btn-danger',
                ]
            ]);
            echo $form->field($model, 'files')->widget(FileInput::classname(), [
                'options' => ['multiple' => false],
                'pluginOptions' => [
                    'showUpload' => false,
                    'showPreview' => false,
                    'showCaption' => true,
                    'uploadClass' => 'btn btn-info',
                    'removeClass' => 'btn btn-danger',
                    'browseLabel' => '',
                ]
            ]);
            echo $form->field($model, 'id', ['options' => ['class' => 'sr-only']])->hiddenInput();
            ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
