<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\FileInput;
use yii\bootstrap\ActiveForm;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$this->title = 'บริหารภาพกิจกรรม';
?>
<div class="row">
    <div class="page-header"><i class="glyphicon glyphicon-picture page-header-icon"></i> <?= Html::encode($this->title) ?><small> [อัลบั้ม: <?= $model->title ?>]</small></div>
</div>
<br/>
<div class="gallery-index">
    <div class="dashboard_box">
        <br/>
        <div class="row">
            <div class="col-sm-12">
                <?php
                $form = ActiveForm::begin([
                            'id' => 'gallery-form',
                            'options' => ['class' => '', 'enctype' => 'multipart/form-data'],
                            'fieldConfig' => [
                                'template' => "{label}\n{input}\n{error}",
                                'labelOptions' => ['class' => 'control-label'],
                            ],
                ]);

                echo $form->field($model, 'upload_files[]')->widget(FileInput::classname(), [
                    'options' => ['multiple' => true],
                    'pluginOptions' => [
                        'showPreview' => true,
                        'showCaption' => false,
                        'uploadClass' => 'btn btn-info',
                        'removeClass' => 'btn btn-danger',
                        'elCaptionText' => '#customCaption'
                    ]
                ]);

                ActiveForm::end();
                ?>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="file-input">
                <div class="file-preview-thumbnails">            
                    <?php
                    $mPath = \Yii::getAlias('@webroot') . '/images/gallery/cat_' . $model->id;
                    $mUrl = \Yii::getAlias('@web') . '/images/gallery/cat_' . $model->id;
                    if (!is_dir($mPath)) {
                        mkdir($mPath);
                        chmod($mPath, '777');
                    }
                    foreach (scandir($mPath) as $img) {
                        if ($img != '.' && $img != '..' && $img != 'thumb') {
                            $mThumb = $mUrl . '/thumb/' . $img;
                            //ตรวจสอบภาพตัวอย่าง ว่าถูกสร้างขึ้นมาหรือยัง
                            if (!file_exists($mThumb)) {
                                //ตรวจสอบโฟลเดอร์ภาพตัวอย่าง
                                if (!is_dir($mPath . '/thumb')) {
                                    mkdir($mPath . '/thumb/');
                                    chmod($mPath . '/thumb/', '777');
                                }
                                //สร้างภาพตัวอ่ย่าง
                                $image = \Yii::$app->image->load($mPath . '/' . $img);
                                $image->resize(250, 250);
                                $image->save($mPath . '/thumb/' . $img);
                            }
                            echo '<div class="file-preview-frame">';
                            echo '<div class="close fileinput-remove text-right"><a href="' . Url::to(['gallery/delimage', 'id' => $model->id, 'file' => $img]) . '">×</a></div>';
                            echo '<img src="' . $mThumb . '" class="file-preview-image"/>';
                            echo '</div>';
                        }
                    }
                    ?>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        
    </div>
</div>
