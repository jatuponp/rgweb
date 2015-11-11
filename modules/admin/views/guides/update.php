<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Categories;
use kartik\widgets\DatePicker;
use kartik\widgets\SwitchInput;
use kartik\widgets\FileInput;
use yii\helpers\Url;
use dosamigos\ckeditor\CKEditor;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\LoginForm $model
 */
$this->title = 'ข้อมูล' . app\models\TblGuides::getCat($model->cid);
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

                <?= $form->field($model, 'titles')->input('text', ['placeholder' => 'พิมพ์ชื่อเรื่องที่นี้']) ?>
                <div class="form-group required" style="padding-left: 0px; padding-right: 10px;">
                    <?php
                    // kcfinder options
                    // http://kcfinder.sunhater.com/install#dynamic
                    $kcfOptions = array_merge(\iutbay\yii2kcfinder\KCFinderInputWidget::$kcfDefaultOptions, [
                        'uploadURL' => Yii::getAlias('@web') . '/images/',
                        'access' => [
                            'files' => [
                                'upload' => true,
                                'delete' => true,
                                'copy' => false,
                                'move' => false,
                                'rename' => true,
                            ],
                            'dirs' => [
                                'create' => true,
                                'delete' => true,
                                'rename' => true,
                            ],
                        ],
                    ]);
                    // Set kcfinder session options
                    Yii::$app->session->set('KCFINDER', $kcfOptions);
                    echo $form->field($model, 'fulltexts')->widget(CKEditor::className(), [
                        'options' => ['rows' => 15],
                        'preset' => 'standard'
                    ]);
                    echo "<br/>";
                    ?>
                    <div class="help-block"></div>
                </div>
                <?= $form->field($model, 'id', ['options' => ['class' => 'sr-only']])->hiddenInput() ?>
                <?= $form->field($model, 'cid', ['options' => ['class' => 'sr-only']])->hiddenInput() ?>
                <?= $form->field($model, 'langs', ['options' => ['class' => 'sr-only']])->hiddenInput() ?>
            </div>
            <div class="col-lg-4">
                <?= $form->field($model, 'amphur')->dropDownList(\app\models\TblAmphur::makeDropDown()) ?>
                <?= $form->field($model, 'address')->input('text') ?>
                <?= $form->field($model, 'contacts')->input('text') ?>
                <?= $form->field($model, 'phone')->input('text') ?>
                <?= $form->field($model, 'fax')->input('text') ?>
                <?= $form->field($model, 'emails')->input('text') ?>
                <?= $form->field($model, 'website')->input('text') ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

