<?php


use common\components\Html;
use yii\widgets\DetailView;
use common\models\Configuration;

$opt='';
$i = Html::tag('i', '', ['class' => 'glyphicon glyphicon-chevron-right pull-right']);

if($save) {

    $pageSettingsContent=[
        Html::tag('li', Yii::t('app', 'Menu') . $i, ['class' => 'list-group-item hasSubpanel', 'data-subpanel' => "sp_menu"]),
        Html::tag('li', Yii::t('app', 'Page theme') . $i, ['class' => 'list-group-item hasSubpanel ', 'data-subpanel' => "sp_theme"]),
        Html::tag('li', Yii::t('app', 'S.E.O.') . $i, ['class' => 'list-group-item hasSubpanel', 'data-subpanel' => "sp_seo"])
    ];

    $lidel = Html::tag('li', Yii::t('app', 'Delete page') . $i, ['class' => 'list-group-item hasSubpanel deletePage', 'data-subpanel' => "sp_delete_page_confirm"]);

    $pageSettingsTabs=[
        Html::tag('span',Yii::t('app','Current page'), ['class' => 'mn_panel_subtitle']),
        Html::tag('span',Yii::t('app','All settings related with the page'), ['class' => 'mn_tip']),
        Html::tag('ul', implode("",$pageSettingsContent), ['class' => 'list-group']),
        Html::tag('ul',$lidel, ['class' => 'list-group uldel'])
    ];
}else{
    $pageSettingsTabs=[];
}
$html=implode("",$pageSettingsTabs);
echo $html;