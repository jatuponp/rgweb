<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\grid\DataColumn;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\LoginForm $model
 */
$this->title = 'จัดการข้อมูลเว็บไซต์';
?>
<div class="site-login">
    <div class="page-header"><?= Html::encode($this->title) ?></div>
    <div class="row">
        <div class="col-lg-4">
            <a href="index.php?r=content/update" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> สร้างเนื้อหา</a>
        </div>
        <div class="col-lg-8">
            <?php
            $form = ActiveForm::begin([
                        'id' => 'content-form',
                        'options' => ['class' => 'form-inline pull-right'],
                        'fieldConfig' => [
                            'template' => "{label}{input}",
                            'labelOptions' => ['class' => 'sr-only'],
                        ],
            ]);
            ?>

            <?= $form->field($model, 'search')->input('text', ['style'=>'width: 300px']); ?>
            <?= Html::submitButton('<i class="glyphicon glyphicon-search"></i> ค้นหา', ['class' => 'btn btn-primary']) ?>

            <?php ActiveForm::end(); ?>
        </div>
    </div><br/>
    <?php
    echo GridView::widget([
        'dataProvider' => $model->search(),
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'headerOptions' => ['width' => '5%'],
            ],
            [
                'headerOptions' => ['width' => '60%'],
                'header' => 'ชื่อเรื่อง',
                'attribute' => 'title',
            ],
            [
                'headerOptions' => ['width' => '30%'],
                'attribute' => 'applyDate',
                'header' => 'ปรับปรุงเมื่อ',
                'format' => ['date', 'd-m-Y H:i:s']
            ],
            [
                'headerOptions' => ['width' => '5%','style'=>'text-align:center;'],
                'contentOptions' => ['align'=>'center'],
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'header' => 'แก้ไข'
            ],
        ]
    ]);
    ?>
</div>
