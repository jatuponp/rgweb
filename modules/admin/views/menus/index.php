<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
use app\models\TblMenutype;
use yii\bootstrap\Modal;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\LoginForm $model
 */
$this->title = 'Menus Management';
//$langs = ($_POST['Categories']['langs']) ? $_POST['Categories']['langs'] : 'thai';
?>
<div class="row">
    <div class="page-header"><i class="glyphicon glyphicon-list page-header-icon"></i> <?= Html::encode($this->title) ?></div>
</div>
<br/>
<div class="site-login">
    <div class="dashboard_box">
        <br/>
        <div class="row">
            <div class="col-lg-4">
                <div class="input-group">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-plus"></i> เพิ่มเมนู <span class="caret"></span></button>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="<?= Url::to(['update', 'type' => 'article', 'parent_id' => $c->id]) ?>">เชื่อมโยงเนื้อหาเว็บไซต์,บทความ</a></li>
                            <li><a href="<?= Url::to(['update', 'type' => 'links', 'parent_id' => $c->id]) ?> ">เชื่อมโยงเว็บไซต์ภายนอก</a></li>
                        </ul>
                    </div><!-- /btn-group -->
                </div>
            </div>
            <div class="col-lg-8">
                <?php
                //Authority Only.
                if (Yii::$app->user->can('Authority')) {
                    Modal::begin([
                        'options' => ['id' => 'new'],
                        'header' => '<h4 style="margin:0; padding:0">ประเภทเมนู </h4>',
                        'toggleButton' => ['label' => '<i class="glyphicon glyphicon-th-large"></i>', 'class' => 'btn btn-danger pull-right'],
                    ]);
                    Pjax::begin();
                    $form = ActiveForm::begin([
                                'id' => 'article-form',
                                'options' => ['data-pjax' => true],
                                'fieldConfig' => [
                                    'template' => "<div class=\"input-group\">{input}<span class=\"input-group-btn\"><button class=\"btn btn-primary\" type=\"submit\">เพิ่ม</button></span></div>{hint}{error}",
                                //'labelOptions' => [ 'class' => 'sr-only'],
                                ],
                    ]);
                    echo GridView::widget([
                        'dataProvider' => $type->search($model->langs),
                        'layout' => '{items}',
                        'columns' => [
                            [
                                'class' => 'yii\grid\SerialColumn',
                                'headerOptions' => ['width' => '5%'],
                            ],
                            [
                                'headerOptions' => ['width' => '88%'],
                                'header' => 'ประเภทเมนู',
                                'attribute' => 'title'
                            ],
                            [
                                'headerOptions' => ['width' => '7%', 'style' => 'text-align:center;'],
                                'contentOptions' => [ 'align' => 'center'],
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{delete}',
                                'buttons' => [
                                    'delete' => function ($url, $model) {
                                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', Url::to(['index', 'action' => 'typedelete', 'id' => $model->id]));
                                    },
                                        ],
                                    ],
                                ]
                            ]);
                            ?>
                            <div style="margin-top: 10px;">
                                <?= $form->field($type, 'title')->input('text', ['placeholder' => 'กรอกชื่อปะรเภท'])->hint('เช่น เมนูหลัก, เมนูด้านซ้าย, ลิงค์ที่น่าสนใจ') ?>
                            </div>
                            <div class="modal-footer">
                                <a href="<?= Url::to(['index']) ?>" data-dismiss="modal" class="btn btn-default">ปิด</a>
                            </div>

                            <?php
                            ActiveForm::end();
                            Pjax::end();
                            Modal::end();
                        }
                        //End Authority Only.
                        ?>
                        <?php
                        $form = ActiveForm::begin([
                                    'id' => 'menus-form',
                                    'options' => ['class' => 'form-inline pull-right'],
                                    'fieldConfig' => [
                                        'template' => "{label}{input}&nbsp;",
                                        'labelOptions' => ['class' => 'sr-only'],
                                    ],
                        ]);
                        ?>

                        <?php
                        echo $form->field($model, 'langs')->dropDownList(\app\models\tblLangs::makeDropDown(), ['style' => 'width: 150px;', 'onchange' => 'form.submit();']);
                        echo "&nbsp;";
                        echo $form->field($model, 'type')->dropDownList(TblMenutype::makeDropDown($model->langs), ['onchange' => 'form.submit();']);
                        ?>

                        <?php ActiveForm::end(); ?>            

                    </div>
                </div><br/>
                <?php
                Pjax::begin();
                echo GridView::widget([
                    'dataProvider' => $model->listCategory($model->langs),
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'headerOptions' => ['width' => '5%'],
                        ],
                        [
                            'headerOptions' => ['width' => '61%'],
                            'header' => 'หมวดหมู่',
                            'value' => function($model) {
                                return $model['names'];
                            },
                        ],
                        [
                            'headerOptions' => ['width' => '7%', 'style' => 'text-align:center;'],
                            'contentOptions' => [ 'align' => 'center'],
                            'class' => 'yii\grid\CMSColumn',
                            'template' => '{published}',
                        ],
                        [
                            'headerOptions' => ['width' => '7%', 'style' => 'text-align:center;'],
                            'contentOptions' => ['align' => 'center'],
                            'class' => 'yii\grid\CMSColumn',
                            'template' => '{up} {down}',
                        ],
                        [
                            'headerOptions' => ['width' => '10%', 'style' => 'text-align:center;'],
                            'contentOptions' => ['align' => 'center'],
                            'class' => 'yii\grid\ActionColumn',
                            //'template' => '{update} &nbsp;{delete}',
                            'header' => 'แก้ไข',
                        ],
                    ]
                ]);
                Pjax::end();
                ?>
    </div>
</div>
