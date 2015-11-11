<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\LoginForm $model
 */
$this->title = 'จัดการหมวดหมู่บทความ';
//$langs = ($_POST['Categories']['langs']) ? $_POST['Categories']['langs'] : 'thai';
?>
<div class="row">
    <div class="page-header"><i class="glyphicon glyphicon-folder-open page-header-icon"></i> <?= Html::encode($this->title) ?></div>
</div>
<br/>
<div class="site-login">
    <div class="dashboard_box">
        <br/>
    <div class="row">
        <div class="col-lg-4">
            <a href="<?= Url::to(['categories/update','langs'=>$model->langs]) ?>" class="btn btn-danger"><i class="glyphicon glyphicon-plus"></i> เพิ่มหมวดหมู่</a>
        </div>
        <div class="col-lg-8">
            <?php
            $form = ActiveForm::begin([
                        'id' => 'categories-form',
                        'options' => ['class' => 'form-inline pull-right'],
                        'fieldConfig' => [
                            'template' => "{label}เลือกภาษา: {input}",
                            'labelOptions' => ['class' => 'sr-only'],
                        ],
            ]);
            ?>

            <?php
            //echo $form->field($model, 'parent_id')->dropDownList(Categories::makeDropDown($model->langs), ['style' => 'margin-right: 10px;', 'onchange' => 'form.submit();']);
//            $langOption = new \appCMS\language\LanguageCms();
            echo $form->field($model, 'langs')->dropDownList(\app\models\tblLangs::makeDropDown(), ['style' => 'width: 120px;', 'onchange' => 'form.submit();']);
            ?>

            <?php ActiveForm::end(); ?>
        </div>
    </div><br/>
    <?php
    echo GridView::widget([
        'dataProvider' => $model->listCategory($model->langs),
        'tableOptions' => ['class'=>'table table-striped'],
        'layout' => "{items}",
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'headerOptions' => ['width' => '5%'],
            ],
            [
                'headerOptions' => ['width' => '85%'],
                'header' => 'หมวดหมู่',
                'attribute' => 'title',
            ],
            [
                'headerOptions' => ['width' => '10%','style'=>'text-align:center;'],
                'contentOptions' => ['align'=>'center'],
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} &nbsp;{delete}',
                'header' => 'แก้ไข',
                
            ],
        ]
    ]);
    ?>
    </div>
</div>
