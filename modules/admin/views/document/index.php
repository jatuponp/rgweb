<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use app\models\Categories;
use \yii\helpers\Url;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\LoginForm $model
 */
$this->title = 'บริหารเอกสารดาวน์โหลด';
?>
<div class="row">
    <div class="page-header"><i class="glyphicon glyphicon-download-alt page-header-icon"></i> <?= Html::encode($this->title) ?></div>
</div>
<br/>
<div class="document-index">
    <div class="dashboard_box">
        <br/>
        <div class="row">
            <div class="col-lg-4">
                <a href="<?= Url::to(['update']) ?>" class="btn btn-danger"><i class="glyphicon glyphicon-plus"></i> เพิ่มแฟ้มเอกสาร</a>
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
                        echo $form->field($model, 'cid')->dropDownList(['9' => 'ข่าวประชาสัมพันธ์บุคลากรคณะบริหารธุรกิจ', '12' => 'หน่วยอาคารและสถานที่'], [ 'style' => 'margin-right: 10px; width: 380px;', 'onchange' => 'form.submit();']);
                        //$langOption = new \appCMS\language\LanguageCms();
                        //echo $form->field($model, 'langs')->dropDownList(\app\models\tblLangs::makeDropDown(), [ 'style' => 'width: 120px;', 'onchange' => 'form.submit();']);
                        ?>
                    </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="col-lg-12">
                        <?= $form->field($model, 'search')->input('text', [ 'style' => 'width: 300px']); ?>
                        <?= Html::submitButton('<i class="glyphicon glyphicon-search"></i> ค้นหา', [ 'class' => 'btn btn-danger']) ?>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div><br/>
        <?php
//    $this->registerJs("$('a[data-pjax]').pjax()");
        \yii\widgets\Pjax::begin();
        echo GridView::widget([
            'id' => 'grid-id',
            'dataProvider' => $model->search(),
            'columns' => [
                [
                    'class' => 'yii\grid\SerialColumn',
                    'headerOptions' => ['width' => '5%'],
                ],
                [
                    'headerOptions' => ['width' => '40%'],
                    'header' => 'ชื่อเรื่อง',
                    'attribute' => 'title',
                ],
                [
                    'headerOptions' => ['width' => '20%'],
                    'header' => 'ชื่อไฟล์',
                    'attribute' => 'file_name',
                ],
                [
                    'headerOptions' => ['width' => '16%'],
                    'header' => 'หมวดหมู่',
                    'attribute' => 'catname',
                ],
                [
                    'headerOptions' => ['width' => '5%', 'style' => 'text-align:center;'],
                    'contentOptions' => [ 'align' => 'center'],
                    'class' => yii\grid\CMSColumn::className(),
                    'template' => '{published}',
                    'header' => 'แสดง',
                    'visible' => Yii::$app->user->can('Publisher')
                ],
                [
                    'headerOptions' => ['width' => '7%', 'style' => 'text-align:center;'],
                    'contentOptions' => [ 'align' => 'center'],
                    'class' => yii\grid\CMSColumn::className(),
                    'template' => '{up} {down}',
                    'header' => 'เรียง',
                    'visible' => Yii::$app->user->can('Administrator')
                ],
                [
                    'headerOptions' => ['width' => '7%', 'style' => 'text-align:center;'],
                    'contentOptions' => [ 'align' => 'center'],
                    'class' => yii\grid\ActionColumn::className(),
                    'template' => '{update} {delete}',
                    'header' => 'แก้ไข',
                ],
            ]
        ]);
        \yii\widgets\Pjax::end();
        ?> 
    </div>
</div>
