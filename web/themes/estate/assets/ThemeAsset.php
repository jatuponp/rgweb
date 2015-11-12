<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\web\themes\estate\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ThemeAsset extends AssetBundle
{
    public $basePath = '@webroot/themes/estate/';
    public $baseUrl = '@web/themes/estate/';
    public $css = [
        'style.css',
        'rs-plugin/css/settings.css',
        'css/carousel.css',
        'css/font-awesome.css',
        'css/flexslider.css',
    ];
    public $js = [
        'js/parallax.js',
        'js/bootstrap-select.js',
        'js/carousel.js',
        'js/rotate.js',
        'js/custom.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
