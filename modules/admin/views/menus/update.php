<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\widgets\SwitchInput;
use kartik\widgets\DepDrop;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\LoginForm $model
 */
$this->title = 'Menus Management';
?>
<div class="site-content">
    <?php
    $form = ActiveForm::begin([
                'id' => 'menus-form',
                'options' => ['class' => '', 'enctype' => 'multipart/form-data'],
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'labelOptions' => ['class' => 'control-label'],
                ],
    ]);
    ?>
    <div class="row">
        <div class="page-header">
            <i class="glyphicon glyphicon-tasks page-header-icon"></i> <?= Html::encode($this->title) ?> [<?php echo ($model->id) ? "แก้ไข" : "สร้างใหม่"; ?>]
            <div class="form-group pull-right">
                <?= Html::submitButton('<i class="glyphicon glyphicon-ok"></i> บันทึกข้อมูล', ['class' => 'btn btn-danger']) ?>
                <?= Html::resetButton('<i class="glyphicon glyphicon-remove"></i> ยกเลิก', ['class' => 'btn']) ?>
            </div>
        </div>
    </div>
    <br/>
    <div class="dashboard_box" style="padding-top: 15px;">
        <div class="row">
            <div class="col-sm-8">
                <?= $form->field($model, 'names')->input('text') ?>
                <?php
                if ($type == 'article') {
                    echo $form->field($model, 'urls')->widget(Select2::classname(), [
                        'data' => array_merge(["" => ""], app\models\Article::makeLink($model->langs)),
                        'options' => ['placeholder' => 'เลือกเนื้อหาเว็บไซต์ หรือ บทความ', 'class' => 'form-control'],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]);
                } else if ($type == 'links') {
                    echo $form->field($model, 'urls')->input('text', ['placeholder' => 'เช่น http://www.nongkhai.go.th']);
                }
                ?>
                <?= $form->field($model, 'description')->textarea() ?>
            </div>
            <div class="col-sm-4">
                <?php
                echo $form->field($model, 'langs')->dropDownList(\app\models\tblLangs::makeDropDown(), ['style' => 'width: 150px;']);
                echo $form->field($model, 'type')->widget(Select2::classname(), [
                    'data' => \app\models\TblMenutype::makeDropDown($model->langs),
                    'hideSearch' => true,
                    'options' => [
                        'placeholder' => 'เลือก...'
                        , 'class' => 'form-control '
                        , 'multiple' => false
                        , 'style' => 'width: 98%;'
                    ],
                ]);
                echo $form->field($model, 'parent_id')->widget(DepDrop::classname(), [
                    'type' => DepDrop::TYPE_SELECT2,
                    'data' => [$model->parent_id => ''],
                    'options' => ['style' => 'width: 98%;'],
                    'select2Options' => ['hideSearch' => true,],
                    'pluginOptions' => [
                        'depends' => [Html::getInputId($model, 'type')], // the id for cat attribute
                        'placeholder' => 'เลือก...',
                        'url' => Url::to(["getsubmenu"]),
                        'initialize' => true
                    ]
                ]);
                ?>            
                <?=
                $form->field($model, 'published')->widget(SwitchInput::classname(), [
                    'pluginOptions' => [
                        'size' => 'normal',
                    ],
                    'inlineLabel' => false,
                ]);
                ?>
                <?=
                $form->field($model, 'target')->widget(SwitchInput::classname(), [
                    'pluginOptions' => [
                        'size' => 'normal',
                        'onText' => 'หน้าต่างใหม่',
                        'offText' => 'หน้าต่างเดิม',
                    ],
                    'inlineLabel' => false,
                ]);
                ?>
            </div>
        </div>

        <?= $form->field($model, 'id', ['options' => ['class' => 'sr-only']])->hiddenInput() ?>
        <?= $form->field($model, 'langs', ['options' => ['class' => 'sr-only']])->hiddenInput() ?>

        <?php ActiveForm::end(); ?>
    </div>
</div>

