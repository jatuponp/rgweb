<?php

use yii\bootstrap\Nav;

Nav::begin();
echo Nav::widget([
    'options' => ['class' => 'nav-pills nav-stacked'],
    'encodeLabels'=>false,
    'items' => [
        ['label' => '&nbsp;', 'options' => ['class' => 'nav-header disabled'],'visible'=> !Yii::$app->user->isGuest],
        ['label' => '<i class="glyphicon glyphicon-pencil"></i> เขียนบทความ', 'url' => ['/cms/article/update'],'visible'=> Yii::$app->user->can('Editor')],
        ['label' => '<i class="glyphicon glyphicon-book"></i> บริหารบทความ', 'url' => ['/admin/article/index'],'visible'=> Yii::$app->user->can('Editor')],
        ['label' => '<i class="glyphicon glyphicon-book"></i> บริหารเอกสารดาวน์โหลด', 'url' => ['/admin/document/index'],'active'=> Yii::$app->controller->id=='document','visible'=> Yii::$app->user->can('Editor')],
        ['label' => '<i class="glyphicon glyphicon-folder-open"></i> บริหารหมวดหมู่บทความ', 'url' => ['/admin/categories/index'],'active'=> (Yii::$app->controller->id=='categories'),'visible'=> Yii::$app->user->can('Authority')],
        ['label' => '<i class="glyphicon glyphicon-tasks"></i> บริหารเมนูเว็บไซต์', 'url' => ['/admin/menus/index'],'active'=> (Yii::$app->controller->id=='menus'),'visible'=> Yii::$app->user->can('Editor')],
        ['label' => '<i class="glyphicon glyphicon-picture"></i> บริหารภาพสไลด์', 'url' => ['/admin/slider/index'],'active'=> (Yii::$app->controller->id=='slider'),'visible'=> Yii::$app->user->can('Administrator')],
        ['label' => '<i class="glyphicon glyphicon-picture"></i> บริหารภาพกิจกรรม', 'url' => ['/admin/gallery/index'],'active'=> (Yii::$app->controller->id=='gallery'),'visible'=> Yii::$app->user->can('Administrator')],
        ['label' => '<i class="glyphicon glyphicon-user"></i> บริหารข้อมูลผู้ใช้', 'url' => ['/admin/user/index'],'active'=> (Yii::$app->controller->id=='user'),'visible'=> Yii::$app->user->can('Authority')],
        ['label' => '<i class="glyphicon glyphicon-stats"></i> สถิติการเยื่ยมชมเว็บไซต์', 'url' => ['/admin/stat/index'],'active'=> (Yii::$app->controller->id=='stat'),'visible'=> !Yii::$app->user->isGuest],        
    ],
]);
Nav::end();

echo '<br/>' . Yii::powered() . ': ' . Yii::getVersion();