<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class MenatemaAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/content/bxslider.css',
        'css/pagesbar.css',
        'css/menaproadmin.css',
        'https://fonts.googleapis.com/css?family=Open+Sans:400,300,700',
        'css/simple-sidebar.css',
        'js/customScrollbar/css/jquery.mCustomScrollbar.min.css',
        'css/jquery-ui.min.css',
        'css/jquery-ui.theme.min.css'
    ];

    public $js = [
        'js/content/jquery-ui.min.js',
        'js/jquery.mjs.nestedSortable-modified.js',
        'js/customScrollbar/jquery.mCustomScrollbar.min.js',
        'js/customScrollbar/jquery.mousewheel-3.0.6.min.js',
        'js/content/bxslider/jquery.bxslider.js',
        'js/ajax-file.js',
        'js/ajax-engine.js',

    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
