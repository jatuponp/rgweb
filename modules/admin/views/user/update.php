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
$this->title = 'จัดการข้อมูลผู้ใช้';
?>
<div class="article-content">

    <?php
    $form = ActiveForm::begin([
                'id' => 'login-form',
                'options' => ['class' => '', 'enctype' => 'multipart/form-data'],
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'labelOptions' => ['class' => 'control-label'],
                ],
    ]);
    ?>
    <div class="row">
        <div class="page-header">
            <i class="glyphicon glyphicon-edit page-header-icon"></i> <?= Html::encode($this->title) ?> [<?php echo ($model->id) ? "แก้ไข" : "สร้างใหม่"; ?>]
            <div class="form-group pull-right">
                <?= Html::submitButton('<i class="glyphicon glyphicon-ok"></i> บันทึกข้อมูล', ['class' => 'btn btn-danger']) ?>
                <?= Html::resetButton('<i class="glyphicon glyphicon-remove"></i> ยกเลิก', ['class' => 'btn', 'onclick' => 'history.back();']) ?>
            </div>
        </div>    
    </div>
    <br/>
    <div class="dashboard_box" style="padding-top: 15px;">
        <div class="row">
            <div class="col-lg-8">
                <div class="row">
                    <div class="col-sm-5">
                        <?php echo $form->field($model, 'firstName')->input('text', ['style' => 'width: 300px;']) ?> 
                    </div>
                    <div class="col-sm-7">
                        <?php echo $form->field($model, 'lastName')->input('text', ['style' => 'width: 300px;']) ?>
                    </div>
                </div>
                <?= $form->field($model, 'username')->input('text', ['style' => 'width: 300px;']) ?>
                <div class="row">
                    <div class="col-sm-5">
                        <?php echo $form->field($model, 'password_hash')->passwordinput(['style' => 'width: 300px;', 'value'=>'']) ?> 
                    </div>
                    <div class="col-sm-7">
                        <?php echo $form->field($model, 'confirmPassword')->passwordinput(['style' => 'width: 300px;']) ?>
                    </div>
                </div>
                <?= $form->field($model, 'authType')->listBox(['Administrator' => 'Administrator','Editor' => 'Editor', 'Publisher'=>'Publisher'], ['style' => 'width: 300px;']) ?>
                <?= $form->field($model, 'email')->input('text', ['style' => 'width: 630px;']) ?>
                <?= $form->field($model, 'id', ['options' => ['class' => 'sr-only']])->hiddenInput() ?>
            </div>
        </div>    
        <?php ActiveForm::end(); ?>
    </div>
</div>
