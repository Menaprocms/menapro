<?php
/*
*   ****************************
*   *       MenaPro 1.0        *
*   ****************************
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@menapro.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade MenaPro to newer
* versions in the future. If you wish to customize MenaPro for your
* needs please refer to http://menapro.com for more information.
*
*  @author Xenon media Burgos <contact25@menapro.com>
*  @copyright  2016 Xenon Media
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*
*  Proudly made in Burgos, Spain.
*
*/


use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Configuration;

$br = "<br>";
$opt = '';
$i = Html::tag('i', '', ['class' => 'glyphicon glyphicon-chevron-left']);
$ir = Html::tag('i', '', ['class' => 'glyphicon glyphicon-chevron-right pull-right']);

$btnAtras = [
    Html::a($i . Yii::t('app', 'Back'), '#', ['class' => 'btn btn-default pull-right btnAtras']),
    $br,
    $br
];
$opt .= implode("", $btnAtras);


$cb = " if($('#configuration-value').is(':checked')){
                    $(cp.containers.save_cont).addClass('hidden');
                }else{
                    $(cp.containers.save_cont).removeClass('hidden');
                }";




$name = isset($webName) ? $webName : Yii::t('app', 'Web Name Default');
$val = 50 - strlen($name);

$webNameContent = [
    Html::label(Yii::t('app', 'Web name'), 'web_name', ['class' => 'page_settings_label']),
    Html::tag('p', $val, ['id' => 'countCharWebName', 'class' => 'countChar']),
    $br,
    Html::input('text', 'web_name', $name, ['id' => 'web_name', 'class' => 'mn_ajax', 'data-action' => 'configuration/webname',  'data-callback' => "cb_webname", 'data-target' => '', 'data-currentval' => $name]),
    $br,
    Html::tag('span', 'Max. 50 characters', ['class' => 'mn_tip'])
];

$uploadLogoForm = [
    Html::label(Yii::t('app', 'Web logo'), 'web_logo', ['class' => 'page_settings_label']),
    $br,
    Html::tag('i', '', ['class' => 'fa fa-spinner fa-2x hidden', 'id' => 'loader_logo']),
    Html::img(Url::base() . '/../img/logo.png?'.intval(microtime(true)), ['class' => 'imageLogoForm', 'id' => 'logo_thumb']),
    Html::beginForm(['configuration/upload'], 'post', ['enctype' => 'multipart/form-data']),
    Html::fileInput('imageFile', '', ['accept' => 'image/jpeg,image/png']),
    Html::hiddenInput('name', 'logo'),
    Html::endForm()
];


$uploadFaviconForm = [
    Html::label(Yii::t('app', 'Web favicon'), 'web_favicon', ['class' => 'page_settings_label']),
    $br,
    Html::tag('i', '', ['class' => 'fa fa-spinner hidden fa-2x', 'id' => 'loader_favicon']),
    Html::img(Url::base() . '/../img/favicon.ico?'.intval(microtime(true)), ['class' => 'imageFaviconForm', 'id' => 'favicon_thumb']),
    Html::beginForm(['configuration/upload'], 'post', ['enctype' => 'multipart/form-data']),
    Html::fileInput('imageFile', '', ['accept' => 'image/ico']),
    Html::hiddenInput('name', 'favicon'),
    Html::endForm()
];

$uaAnalyticsContent = [
    Html::label(Yii::t('app', 'UA Analytics'), 'ua_analytics', ['class' => 'page_settings_label']),
    Html::input('text', 'ua_analytics', $ua_analytics_code, ['id' => 'ua_analytics', 'class' => 'mn_ajax', 'data-action' => 'configuration/uaanalytics', 'data-callback' => '', 'data-target' => '', 'data-currentval' => $ua_analytics_code]),
];
$gmapApiKeyContent = [
    Html::label(Yii::t('app', 'GMap Api key'), 'gmap_api_key', ['class' => 'page_settings_label']),
    Html::input('text', 'gmap_api_key', $gmap_api_key, ['id' => 'gmap_api_key', 'class' => 'mn_ajax', 'data-action' => 'configuration/gmapapikey', 'data-callback' => 'cb_gmap_api_key', 'data-target' => '', 'data-currentval' => $gmap_api_key]),
];

$chkOptions=['id' => 'configuration-value', 'class' => 'onoffswitch-checkbox mn_ajax', 'data-action' => 'configuration/togglebootstrap', 'data-callback'=>'', 'data-target' => ''];
if(Configuration::getValue('_BOOTSTRAP4_')=='1')
{
    $chkOptions['checked']='checked';
}
$inpBootstrap = Html::input('checkbox', 'Configuration[value]', true,$chkOptions);
$lblBootstrap = Html::label('', 'configuration-value', ['class' => 'onoffswitch-label']);

$bootstrapMode = [
    Yii::t('app', 'Bootstrap 4').$br,
    Html::tag('div', $inpBootstrap . $lblBootstrap, ['class' => 'onoffswitch pull-right']),
    Html::tag('span',Yii::t('app','Warning: Enable only this when your theme require it.'),['class'=>'mn_tip'])
];
/********************/
$chkOptionsCache=['id' => 'configuration-value-cache', 'class' => 'onoffswitch-checkbox mn_ajax', 'data-action' => 'configuration/togglecache', 'data-callback'=>'', 'data-target' => ''];
if(Configuration::getValue('_ENABLE_CACHE_')=='1')
{
    $chkOptionsCache['checked']='checked';
}
$inpCache = Html::input('checkbox', 'Configuration[value]', true,$chkOptionsCache);
$lblCache = Html::label('', 'configuration-value-cache', ['class' => 'onoffswitch-label']);
$cache=[
    Yii::t('app', 'Enable cache'),
    Html::tag('div', $inpCache . $lblCache, ['class' => 'onoffswitch pull-right']),
    //Html::tag('span',Yii::t('app','Warning: Enable only this if you want to show cookies notification.'),['class'=>'mn_tip'])
];

/********************/

$chkOptionsCookies=['id' => 'configuration-value-cookies', 'class' => 'onoffswitch-checkbox mn_ajax', 'data-action' => 'configuration/togglecookiesnotification', 'data-callback', 'data-target' => ''];
if(Configuration::getValue('_COOKIES_NOTIFICATION_')=='1')
{
    $chkOptionsCookies['checked']='checked';
}
$inpCookies = Html::input('checkbox', 'Configuration[value]', true,$chkOptionsCookies);
$lblCookies = Html::label('', 'configuration-value-cookies', ['class' => 'onoffswitch-label']);
$cookiesNot=[
    Yii::t('app', 'Cookies Notifications'),
    Html::tag('div', $inpCookies . $lblCookies, ['class' => 'onoffswitch pull-right']),
    //Html::tag('span',Yii::t('app','Warning: Enable only this if you want to show cookies notification.'),['class'=>'mn_tip'])
];

$compressorAction=['id' => 'compressor', 'class' => 'onoffswitch-checkbox mn_ajax', 'data-action' => 'configuration/togglecompressor', 'data-callback'=>'', 'data-target' => ''];
if(Configuration::getValue('_COMPRESS_HTML_')=='1')
{
    $compressorAction['checked']='checked';
}
$inpcompressor = Html::input('checkbox', 'compressor', true,$compressorAction);
$lblcompressor = Html::label('', 'compressor', ['class' => 'onoffswitch-label']);

$compressorMode = [
    Yii::t('app', 'Compress html').$br,
    Html::tag('div', $inpcompressor . $lblcompressor, ['class' => 'onoffswitch pull-right']),
    Html::tag('span',Yii::t('app','Html, css and javascript compressor. Default: enabled.'),['class'=>'mn_tip'])
];


$generalSettingsTabs = [
    Html::beginTag('ul', ['class' => "list-group"]),
    Html::tag('li',Html::tag('i','',['class'=>'fa fa-paint-brush'])." ". Yii::t('app', 'Web theme') . $ir, ['class' => 'list-group-item hasSubpanelLevel', 'data-subpanel' => "sp_general_theme", 'data-parent' => 'sp_general_settings']),
    Html::endTag("ul"),
    Html::tag('li', implode("", $webNameContent), ['class' => 'list-group-item']),
    Html::tag('li', implode("", $uploadLogoForm), ['class' => 'list-group-item']),
    Html::tag('li', implode("", $uploadFaviconForm), ['class' => 'list-group-item']),

    Html::tag("span", Yii::t('app', 'Api keys '), ['class' => "mn_panel_subtitle"]),
    Html::tag("span", Yii::t('app', 'Some functions require an api key or code to work properly. '), ['class' => "mn_tip"]),
    Html::beginTag('ul', ['class' => "list-group"]),
    Html::tag('li', implode("", $uaAnalyticsContent), ['class' => 'list-group-item']),
    Html::tag('li', implode("", $gmapApiKeyContent), ['class' => 'list-group-item']),
    Html::endTag("ul"),

    Html::tag("span", Yii::t('app', 'Others'), ['class' => "mn_panel_subtitle"]),

    Html::beginTag('ul', ['class' => "list-group"]),
    Html::tag('li', Yii::t('app', 'Cache') . Html::tag('span', "Clear",
            [
                'class' => 'btn btn-xs btn-default pull-right mn_ajax',
                'data-action' => 'configuration/clearcache',
                'data-callback' => 'clearcache',
                'data-target' => ''
            ]
        ), ['class' => 'list-group-item']),
    Html::tag('li', implode("", $cache), ['class' => 'list-group-item', 'data-subpanel' => ""]),
    Html::tag('li', implode("", $cookiesNot), ['class' => 'list-group-item', 'data-subpanel' => ""]),
    Html::tag('li', implode("", $bootstrapMode), ['class' => 'list-group-item', 'data-subpanel' => ""]),
    Html::tag('li', implode("", $compressorMode), ['class' => 'list-group-item', 'data-subpanel' => ""]),
    Html::endTag("ul"),


];
$content = [
    Html::tag('div', Yii::t('app', 'General settings'), ['class' => 'mn_subpanel_title']),
    $opt,
    Html::tag('ul', implode("", $generalSettingsTabs), ['class' => 'list-group'])
];
$wrapper = Html::tag('div', implode("", $content), ["class" => "mn_panel_wrapper"]);
$div1 = Html::tag('div', $wrapper, ['class' => 'mn_scrollbar sp_with_scrollbar']);
$div = Html::tag('div', $div1, ['class' => 'hidden sub_panel', 'title' => Yii::t('app', 'General'), 'id' => 'sp_general_settings']);

echo $div;