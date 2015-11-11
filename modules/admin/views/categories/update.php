<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;
use app\models\Categories;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\LoginForm $model
 */
$this->title = 'บริหารหมวดหมู่บทความ';
?>
<div class="site-content">    
    <?php
    $form = ActiveForm::begin([
                'id' => 'categories-form',
                'options' => ['class' => ''],
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'labelOptions' => ['class' => 'control-label'],
                ],
    ]);
    ?>
    <div class="row">
        <div class="page-header">
            <i class="glyphicon glyphicon-folder-open page-header-icon"></i> <?= Html::encode($this->title) ?> [<?php echo ($model->id) ? "แก้ไข" : "สร้างใหม่"; ?>]
            <div class="form-group pull-right">
                <?= Html::submitButton('<i class="glyphicon glyphicon-ok"></i> บันทึกข้อมูล', ['class' => 'btn btn-danger']) ?>
                <?= Html::resetButton('<i class="glyphicon glyphicon-remove"></i> ยกเลิก', ['class' => 'btn']) ?>
            </div>
        </div> 
    </div>
    <br/>
    <div class="dashboard_box" style="padding-top: 15px;">
        <div class="row">
            <div class="col-lg-8">            
                <?= $form->field($model, 'title')->input('text', ['style' => 'width: 400px;']) ?>
                <div class="form-group required" style="padding-left: 0px; padding-right: 0px;">
                    <label>คำอธิบายหมวดหมู่</label>
                    <?php
                    echo $form->field($model, 'description')->widget(CKEditor::className(), [
                        'options' => ['rows' => 5],
                        'preset' => 'standard'
                    ]);
                    ?>
                    <div class="help-block"></div>
                </div>    
                <?= $form->field($model, 'id', ['options' => ['class' => 'sr-only']])->hiddenInput() ?>
                <?= $form->field($model, 'langs', ['options' => ['class' => 'sr-only']])->hiddenInput() ?>
            </div>
            <div class="col-lg-4">
                <?php
                echo $form->field($model, 'langs')->dropDownList(\app\models\tblLangs::makeDropDown(), ['style' => 'width: 150px;', 'disabled' => true]);
                echo $form->field($model, 'parent_id')->dropDownList(Categories::makeDropDown($model->langs), ['style' => 'margin-right: 10px;width: 300px;']);
                ?>
            </div>

        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>

