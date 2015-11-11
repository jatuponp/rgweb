<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\LoginForm $model
 */
$this->title = 'จัดการข้อมูลผู้ใช้';
?>
<div class="row">
    <div class="page-header"><i class="glyphicon glyphicon-user page-header-icon"></i> <?= Html::encode($this->title) ?></div>
</div>
<br/>
<div class="site-login">
    <div class="dashboard_box">
        <br/>
        <div class="row">
            <div class="col-lg-4">
                <a href="<?= yii\helpers\Url::to(['update']) ?>" class="btn btn-danger"><i class="glyphicon glyphicon-plus"></i> เพิ่มผู้ใช้</a>
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
                    'headerOptions' => ['width' => '10%'],
                    'header' => 'Username',
                    'attribute' => 'username',
                ],
                [
                    'headerOptions' => ['width' => '20%'],
                    'header' => 'Name',
                    'value' => function($model) {
                return $model->firstName . ' ' . $model->lastName;
            },
                ],
                [
                    'headerOptions' => ['width' => '40%'],
                    'header' => 'Email',
                    'attribute' => 'email',
                ],
                [
                    'headerOptions' => ['width' => '20%'],
                    'attribute' => 'update_time',
                    'header' => 'Last Login',
                    'format' => ['date', 'j/n/Y H:i:s']
                ],
                [
                    'headerOptions' => ['width' => '5%', 'style' => 'text-align:center;'],
                    'contentOptions' => ['align' => 'center'],
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update} {delete}',
                    'header' => 'Edit'
                ],
            ]
        ]);
        ?>
    </div>
</div>
