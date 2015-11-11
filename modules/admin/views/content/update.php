<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii2elRTE\yii2elRTE;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\LoginForm $model
 */
$this->title = 'Content Management';
?>
<div class="site-content">
    <h2><?= Html::encode($this->title) ?> [<?php echo ($model->id) ? "แก้ไข" : "สร้างใหม่"; ?>]</h2>    
    <hr/>
    <?php
    $form = ActiveForm::begin([
                'id' => 'login-form',
                'options' => ['class' => ''],
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'labelOptions' => ['class' => 'control-label'],
                ],
    ]);
    ?>

    <?= $form->field($model, 'title')->input('text', ['style' => 'width: 400px;']) ?>
    <div class="form-group required" style="padding-left: 0px; padding-right: 0px;">
        <label>รายละเอียด</label>
        <?php
        $url1 = Yii::$app->getAssetManager()->publish(Yii::getAlias('@yii2elRTE'));
        echo yii2elRTE::widget(
                array(
                    'model' => $model,
                    'modelName' => 'Content',
                    'attribute' => 'fulltexts',
                    'baseUrl' => $url1[1],
                )
        );
        ?>
        <div class="help-block"></div>
    </div>
    <?= $form->field($model, 'id')->hiddenInput() ?>

    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <?= Html::submitButton('<i class="glyphicon glyphicon-ok"></i> บันทึกข้อมูล', ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton('<i class="glyphicon glyphicon-remove"></i> ยกเลิก', ['class' => 'btn']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>

