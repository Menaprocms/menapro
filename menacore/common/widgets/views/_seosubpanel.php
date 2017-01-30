<?php
/* @var $this \yii\web\View */


use common\components\Html;



$br = "<br>";
$opt = '';
$i = Html::tag('i', '', ['class' => 'glyphicon glyphicon-chevron-left']);

$btnAtras = [
    Html::a($i . Yii::t('app', 'Back'), '#', ['class' => 'btn btn-default pull-right btnAtras']),
    $br,
    $br
];
$opt .= implode("", $btnAtras);

$list = '';

$linkRewrite = isset($model->langFields[0]) ? $model->langFields[0]->link_rewrite : '';
$link_rewriteContent = [
    Html::label(Yii::t('app', 'Friendly URL'), 'link_rewrite', ['class' => 'page_settings_label']),
    $br,
    Html::input('text', 'ContentLang[link_rewrite]', $linkRewrite, [
        'id' => 'contentlang-link_rewrite',
        'class' => 'mn_ajax',
        'data-action' => 'content/linkrewrite',
        'data-info' => array('id' => $model->id),
        'data-beforesend'=>"bs_link_rewrite",
        'data-callback' => "cb_link_rewrite",
        'data-currentval' => $linkRewrite]
    )
];

$metaTitulo = isset($model->langFields[0]) ? $model->langFields[0]->meta_title : '';

$metatitleContent = [
    Html::label(Yii::t('app', 'Metatitle'), 'meta_title', ['class' => 'page_settings_label']),
    $br,
    Html::input('text', 'ContentLang[meta_title]', $metaTitulo,
        [
            'id' => 'contentlang-meta_title',
            'class' => 'mn_ajax',
            'data-action' => 'content/metatitle',
            'data-info' => array('id' => $model->id),
            'data-currentval' => $metaTitulo,
            'data-callback'=>"cb_metatitle"
        ]),
];

$metaDescription = isset($model->langFields[0]) ? $model->langFields[0]->meta_description : '';
$val = 256 - strlen($model->langFields[0]->meta_description);

$metadescriptionContent = [
    Html::label(Yii::t('app', 'Metadescription'), 'meta_description', ['class' => 'page_settings_label']),
    Html::tag('p', $val, ['id' => 'countCharMetadescription', 'class' => 'countChar']),
    $br,
    Html::textarea('ContentLang[meta_title]', $metaDescription, [
        'id' => 'contentlang-meta_description',
        'class' => 'mn_ajax form-control',
        'maxlength' => 256,
        'data-action' => 'content/metadescription',
        'data-info' => array('id' => $model->id),
        'data-callback' => 'cb_metadescription',
        'data-currentval' => $metaDescription]),
    $br,
    Html::tag('span', 'Max. 256 characters', ['class' => 'mn_tip'])
];

$seoSettingsList = [
    Html::tag('li', implode("", $link_rewriteContent), ['class' => 'list-group-item']),
    Html::tag('li', implode("", $metatitleContent), ['class' => 'list-group-item']),
    Html::tag('li', implode("", $metadescriptionContent), ['class' => 'list-group-item'])
];

$content = [
    Html::tag('div', Yii::t('app', 'SEO settings'), ['class' => 'mn_subpanel_title']),
    $opt,
    Html::tag('ul', implode("", $seoSettingsList), ['class' => 'list-group'])
];

$wrapper = Html::tag('div', implode("", $content), ['class' => "mn_panel_wrapper"]);

$div = Html::tag('div', $wrapper, ['class' => 'hidden sub_panel', 'title' => Yii::t('app', 'SEO'), 'id' => 'sp_seo']);
echo $div;