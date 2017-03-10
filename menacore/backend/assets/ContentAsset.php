<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\assets;

use yii\web\AssetBundle;
use common\models\Block;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ContentAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/miniaturas.css',
        'css/content/common.css',
        'css/content/editor.css',
        'css/content/probox.css',
        'css/select2.min.css',
        'css/content/font-awesome-4.6.3/css/font-awesome.min.css',
        'banana/css/loader.css',
//        'css/content/font-awesome/css/font-awesome.min.css',
//        Eros
        'banana/css/banana.css',

//    Fin Eros
    //textext
        'js/textext/css/textext.core.css',
        'js/textext/css/textext.plugin.autocomplete.css',
        'js/textext/css/textext.plugin.tags.css',
    //textext

    ];
    public $js = [
        'js/content/fancybox/jquery.fancybox.js',
        'js/content/tinymce/tinymce.min.js',
        'js/html2canvas-modified.js',
        'js/content/typeahead/typeahead.bundle.js',
        'js/content/typeahead/bloodhound.js',
        'js/select2.full.min.js',

//        EROS 30 Junio
        'js/jsblocksE.js',
        'js/content/menacoreE.js',
        'js/content/createHtmlE.js',
        'js/content/proboxE.js',
        'banana/js/banana.js',
//        FIN EROS 30 Junio
        'js/content/eTools.js',
        //textext
        'js/textext/js/textext.core.js',
        'js/textext/js/textext.plugin.autocomplete.js',
        'js/textext/js/textext.plugin.tags-modified.js',
        'js/textext/js/textext.plugin.suggestions.js',
        'js/textext/js/textext.plugin.filter.js',
        //textext
        //news
        'js/newsmanagement.js'
        //news
    ];

    public $depends = [
        'yii\web\YiiAsset',
//        'yii\jui\JuiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
