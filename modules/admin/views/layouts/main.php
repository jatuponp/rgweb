<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

$this->registerJs('$().scroll(function(){$(".app-title").css("top",Math.max(0,250-$(this).scrollTop()));});');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
        <link href="<?= Yii::getAlias('@web') ?>/css/cms.css" rel="stylesheet">
    </head>
    <body>
        <?php $this->beginBody() ?>
        <?php
        NavBar::begin([
            'brandLabel' => '<div></div>',
            'brandUrl' => Yii::$app->homeUrl,
            'innerContainerOptions' => [
                'class' => 'container-fluid'
            ],
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top',
                'style' => 'padding-right: 15px;'
            ],
        ]);

        $menuItems1 = [
            ['label' => '<i class="glyphicon glyphicon-th" style="font-size: 28px;"></i>', 'url' => ['/admin/default/index']],
            ['label' => '<i class="glyphicon glyphicon-user" style="font-size: 28px;"></i>', 'url' => ['/admin/user/index']],
            ['label' => '<i class="glyphicon glyphicon-off" style="font-size: 28px;"></i>', 'url' => ['/site/logout'], 'linkOptions' => ['data-method' => 'post']]
        ];

        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'encodeLabels' => false,
            'items' => $menuItems1,
        ]);

        NavBar::end();
        ?>

        <div class="container-fluid fill">
            <div class="row" style="height: 100%; min-height: 100%;">
                <div class="col-xs-3 col-md-2 cont">
                    <?php require 'menus.php'; ?>
                </div>
                <div class="col-xs-12 col-sm-9 col-md-10" style="overflow:auto; height: 100%; min-height: 100%; box-shadow: 0px 0px 15px 0px rgba(0, 0, 0, 0.3), 0px 0px 0px 0px rgba(255, 255, 255, 0.9) inset; background-color: #FCFCFC;">
                    <?= $content ?>
                </div>
            </div>
        </div>

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
