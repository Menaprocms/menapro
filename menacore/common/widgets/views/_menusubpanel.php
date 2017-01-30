<?php


use common\components\Html;
use yii\widgets\DetailView;
use common\models\Configuration;
$br = "<br>";
$opt = '';
$i = Html::tag('i', '', ['class' => 'glyphicon glyphicon-chevron-left']);

$btnAtras = [
    Html::a($i . Yii::t('app', 'Back'), '#', ['class' => 'btn btn-default pull-right btnAtras']),
    $br,
    $br
];
$opt .= implode("", $btnAtras);

//$cb='$("#menuItem_'.$model->id.'").toggleClass("mn_page_not_in_menu");cms.toggleMenuStatus('.$model->id.')';
$options = [
    'id' => 'content-in_menu',
    'class' => 'onoffswitch-checkbox mn_ajax',
    'data-action' => 'content/togglemenu',
    'data-info' => array('id' => $model->id),
    'data-callback' => "cb_in_menu",
    'data-target' => ''
];

if ($save) {
    if ($model['in_menu'] === 1) {
        $options['checked']="checked";
    }
}
$inp = Html::input('checkbox', 'Content[in_menu]', true, $options);



$divCheckContent = [
    $inp,
    Html::label('', 'content-in_menu', ['class' => 'onoffswitch-label '])
];

$showinmenuContent = [
    Html::label(Yii::t('app', 'Show in menu'), 'content-in_menu', ['class' => 'page_settings_label']),
    Html::tag('div', implode("", $divCheckContent), ['class' => 'onoffswitch pull-right'])
];
$li = Html::tag('li', implode("", $showinmenuContent), ['class' => 'list-group-item ']);

$menuText = isset($model->langFields[0]) ? $model->langFields[0]->menu_text : '';

$menutextContent = [
    Html::label(Yii::t('app', 'Menu text'), 'menu_text', ['class' => 'page_settings_label']),
    $br,
    Html::input('text', 'ContentLang[menu_text]', $menuText, ['id' => 'contentlang-menu_text', 'class' => 'mn_ajax', 'data-action' => 'content/menutext', 'data-info' => array('id' => $model->id), 'data-callback' => 'cb_menutext', 'data-target' => '', 'data-currentval' => $menuText])
];
$li2 = Html::tag('li', implode("", $menutextContent), ['class' => 'list-group-item']);
$ul = Html::tag('ul', $li2, ['class' => 'list-group']);

$content = [
    Html::tag('div', Yii::t('app', 'Menu settings'), ['class' => 'mn_subpanel_title']),
    $opt,
    Html::tag('ul', $li, ['class' => 'list-group']),
    Html::tag('div', $ul, ['class' => $model['in_menu'] === 1?"":'hidden', 'id' => 'menu_sub_options'])
];

$wrapper = Html::tag('div', implode("", $content), ['class' => "mn_panel_wrapper"]);

$div = Html::tag('div', $wrapper, ['class' => 'hidden sub_panel', 'title' => Yii::t('app', 'Menu'), 'id' => 'sp_menu']);
echo $div;