<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\grid\GridView;

$this->title = 'บริหารภาพกิจกรรม';
?>
<div class="row">
    <div class="page-header"><i class="glyphicon glyphicon-picture page-header-icon"></i> <?= Html::encode($this->title) ?></div>
</div>
<br/>
<div class="gallery-index">
    <div class="dashboard_box">
        <br/>
        <div class="row">
            <div class="col-lg-4">
                <a href="<?= Url::to(['update']) ?>" class="btn btn-danger"><i class="glyphicon glyphicon-plus"></i> เพิ่มข้อมูล</a>
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
                <?php ActiveForm::end(); ?>
            </div>
        </div><br/>
        <?php
        echo GridView::widget([
            'dataProvider' => $model->search($model->langs),
            'columns' => [
                [
                    'class' => 'yii\grid\SerialColumn',
                    'headerOptions' => ['width' => '5%'],
                ],
                [
                    'headerOptions' => ['width' => '85%'],
                    'format' => 'raw',
                    'value' => function($model) {
                $mPath = \Yii::getAlias('@webroot') . '/images/gallery/cat_' . $model->id;
                $mUrl = \Yii::getAlias('@web') . '/images/gallery/cat_' . $model->id;
                if (!is_dir($mPath)) {
                    mkdir($mPath);
                    chmod($mPath, '777');
                }
                foreach (scandir($mPath) as $img) {
                    if ($img != '.' && $img != '..' && $img != 'thumb') {
                        $mThumb = $mUrl . '/thumb/' . $img;
                    }
                }
                $html = '<a href="' . Url::to(['gallery/view', 'id' => $model->id]) . '">';
                $html .= '<img src="' . $mThumb . '" width="120px" style="float:left;margin-right:5px;"/>';
                $html .= $model->title . '</a>';
                return $html;
            },
                ],
                [
                    'headerOptions' => ['width' => '10%', 'style' => 'text-align:center;'],
                    'contentOptions' => ['align' => 'center'],
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update} &nbsp;{delete}',
                    'header' => 'แก้ไข',
                ],
            ]
        ]);
        ?>
    </div>
</div>

