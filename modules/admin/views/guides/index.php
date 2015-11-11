<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\LoginForm $model
 */
$this->title = 'ข้อมูล' . app\models\TblGuides::getCat($model->cid);
?>
<div class="row">
    <div class="page-header"><i class="glyphicon glyphicon-book page-header-icon"></i>&nbsp;<?= Html::encode($this->title) ?></div>
</div>
<br/>
<div class="site-login">
    <div class="dashboard_box">
        <br/>
        <div class="row">
            <div class="col-lg-4">
                <a href="<?= Url::to(['update', 'cid' => $model->cid]) ?>" class="btn btn-danger"><i class="glyphicon glyphicon-plus"></i> เพิ่มข้อมูล</a>
            </div>
            <div class="col-lg-8">
                <?php
                $form = ActiveForm::begin([
                            'id' => 'article-form',
                            'options' => [ 'class' => 'form-inline pull-right'],
                            'fieldConfig' => [
                                'template' => "{label}{input}",
                                'labelOptions' => [ 'class' => 'sr-only'],
                            ],
                ]);
                ?>
                <div class="row">
                    <div class="col-lg-12">
                        <?php
                        echo $form->field($model, 'amphur')->dropDownList(\app\models\TblAmphur::makeDropDown(), [ 'style' => 'margin-right: 10px; width: 250px;', 'onchange' => 'form.submit();']);
                        echo $form->field($model, 'langs')->dropDownList(\app\models\tblLangs::makeDropDown(), [ 'style' => 'width: 120px;', 'onchange' => 'form.submit();']);
                        ?>
                    </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="col-lg-12">
                        <?= $form->field($model, 'search')->input('text', [ 'style' => 'width: 300px']); ?>
                        <?= Html::submitButton('<i class="glyphicon glyphicon-search"></i> ค้นหา', [ 'class' => 'btn btn-danger']) ?>
                    </div>
                </div>
                <?= $form->field($model, 'cid', ['options' => ['class' => 'sr-only']])->hiddenInput() ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div><br/>
        <?php
        Pjax::begin();
        echo GridView::widget([
            'dataProvider' => $model->search($sess),
            'columns' => [
                [
                    'class' => 'yii\grid\SerialColumn',
                    'headerOptions' => ['width' => '5%'],
                ],
                [
                    'headerOptions' => ['width' => '60%'],
                    'header' => 'ชื่อเรื่อง',
                    'attribute' => 'titles',
                ],
                [
                    'headerOptions' => ['width' => '20%'],
                    'header' => 'ปรับปรุงเมื่อ',
                    'attribute' => 'applyDate',
                ],
                [
                    'headerOptions' => ['width' => '5%', 'style' => 'text-align:center;'],
                    'contentOptions' => [ 'align' => 'center'],
                    'class' => yii\grid\CMSColumn::className(),
                    'template' => '{published}',
                    'header' => 'แสดง',
                ],
                [
                    'headerOptions' => ['width' => '10%', 'style' => 'text-align:center;'],
                    'contentOptions' => [ 'align' => 'center'],
                    'class' => yii\grid\ActionColumn::className(),
                    'template' => '{update} {delete}',
                    'header' => 'แก้ไข',
                ],
            ]
        ]);
        Pjax::end();
        ?> 
    </div>
</div>
