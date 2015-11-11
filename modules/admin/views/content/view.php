<?php

use yii\helpers\Html;

$this->title = $model->title;
?>
<div class="site-login">
    <h2><?= Html::encode($this->title) ?></h2>    
    <hr/>
    <?= $model->fulltexts ?>
</div>
