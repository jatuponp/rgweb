<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii2elRTE\yii2elRTE;
use app\models\Categories;
use kartik\widgets\DatePicker;
use kartik\widgets\SwitchInput;
use kartik\widgets\FileInput;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\LoginForm $model
 */
$this->title = 'แฟ้มเอกสาร';
?>
<div class="article-content">
    <?php
    $form = ActiveForm::begin([
                'id' => 'login-form',
                'options' => ['class' => '', 'enctype' => 'multipart/form-data'],
                'fieldConfig' => [
                    'template' => "{label}\n{input}{hint}\n{error}",
                    'labelOptions' => ['class' => 'control-label'],
                ],
    ]);
    ?>
    <div class="row">
        <div class="page-header">
            <i class="glyphicon glyphicon-file page-header-icon"></i> <?= Html::encode($this->title) ?> [<?php echo ($model->id) ? "แก้ไข" : "สร้างใหม่"; ?>]
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
            <?= $form->field($model, 'cid')->dropDownList(['9' => 'ข่าวประชาสัมพันธ์บุคลากรคณะบริหารธุรกิจ', '12' => 'หน่วยอาคารและสถานที่'], [ 'style' => 'margin-right: 10px; width: 300px;']); ?>
            <?= $form->field($model, 'title')->input('text', ['placeholder' => 'พิมพ์ชื่อเอกสารที่นี้']) ?>
            <?=
            $form->field($model, 'import')->widget(FileInput::classname(), [
                'pluginOptions' => [
                    'showPreview' => false,
                    'showCaption' => true,
                    'showRemove' => true,
                    'showUpload' => false,
                    'initialCaption' => (($model->file_name) ? $model->file_name : '' )
                ]
            ])->hint(' *.doc, *.docx, *.xls, *.xlsx *.pdf')
            ?>
            <?= $form->field($model, 'id', ['options' => ['class' => 'sr-only']])->hiddenInput() ?>
            <?= $form->field($model, 'langs', ['options' => ['class' => 'sr-only']])->hiddenInput() ?>
        </div>
    </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>

