<?php

use yii\helpers\Html;
use yii\helpers\Url;
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
        <div class="col-sm-12">
            <?php
            echo $form->field($model, 'titles')->input('text', ['style' => 'width: 400px;']);
            echo $form->field($model, 'vdo')->input('text', ['style' => 'width: 400px;']);
            echo $form->field($model, 'id', ['options' => ['class' => 'sr-only']])->hiddenInput();
            ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
