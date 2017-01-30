<?php


use common\components\Html;
use yii\widgets\DetailView;
use common\models\Configuration;


$br="<br>";
$i = Html::tag('i', '', ['class' => 'glyphicon glyphicon-chevron-right pull-right']);

$logoutButton=Html::a(
    Yii::$app->user->isGuest?"":ucfirst(Yii::$app->user->identity->username)
    ." (" .Yii::t('app', 'Logout').")",
    [
        'site/logout',
    ],[
    'data'=>[
//        'confirm'=>Yii::t('app', 'Do you really want to exit?'),
        'method'=>'post',

    ],
    'id'=>'logout_btn'
]);


$liLogout=Html::tag('li',$logoutButton,['class'=>'list-group-item']);

$spvsWarnCount=isset($supervisorMessages[2])?count($supervisorMessages[2]):false;
$spvsDangCount=isset($supervisorMessages[3])?count($supervisorMessages[3]):false;
$webSettingsTabs=[
    Html::tag('li',Yii::t('app', 'General').$i,['class'=>'list-group-item hasSubpanel','data-subpanel'=>"sp_general_settings"]),
    Html::tag('li',Yii::t('app', 'Social').$i,['class'=>'list-group-item hasSubpanel','data-subpanel'=>"sp_social_settings"]),
    Html::tag('li',Yii::t('app', 'Contact').$i,['class'=>'list-group-item hasSubpanel','data-subpanel'=>"sp_contact_settings"]),
    Html::tag('li',Yii::t('app', 'Blocks').$i,['class'=>'list-group-item hasSubpanel','data-subpanel'=>"sp_block_settings"]),
    Html::tag('li',Yii::t('app', 'Language').$i,['class'=>'list-group-item hasSubpanel','data-subpanel'=>"sp_language_settings"]),
    Html::beginTag('li',['class'=>'list-group-item hasSubpanel','data-subpanel'=>"sp_supervisor"]),
    Html::beginTag('span',['id'=>'supervisor_badge']),
    $spvsDangCount?
        Html::tag('span',$spvsDangCount.Html::fa("exclamation-circle"),['class'=>'label label-danger'])." ":"",
    $spvsWarnCount?
        Html::tag('span',$spvsWarnCount.Html::fa("exclamation-triangle"),['class'=>'label label-warning'])." ":"",
    Html::endTag('span'),
    Yii::t('app', 'Security').$i,
    Html::endTag('li'),
//    Html::tag('li',
//        ($spvsDangCount?
//            Html::tag('span',$spvsDangCount.Html::fa("exclamation-circle"),['class'=>'btn btn-xs btn-danger'])." ":"")
//        .($spvsWarnCount?
//            Html::tag('span',$spvsWarnCount.Html::fa("exclamation-triangle"),['class'=>'btn btn-xs btn-warning'])." ":"")
//        .Yii::t('app', 'Security').$i,['class'=>'list-group-item hasSubpanel','data-subpanel'=>"sp_supervisor"]),

];
$gsContent=[
    Html::tag('span',Yii::t('app','Web'),['class'=>'mn_panel_subtitle']),
    Html::tag('span',Yii::t('app','All settings related with website'),['class'=>'mn_tip']),
    $br,
    Html::tag('ul',implode("",$webSettingsTabs),['class'=>'list-group']),
    $br,
    Html::tag('ul',$liLogout.Html::tag('li',Yii::t('app', 'My Account').$i,['class'=>'list-group-item hasSubpanel','data-subpanel'=>"sp_myaccount"]),['class'=>'list-group '.(Yii::$app->user->isGuest?'hidden':'')])
];
$settingsDivs=[
    Html::tag('div','',['id'=>'page_settings']),
    Html::tag('div',implode("",$gsContent),['id'=>'general_settings'])
];
$settings=Html::tag('div',implode("",$settingsDivs),['id'=>'settings']);

$div4=Html::tag('div',$settings,['class'=>'mn_panel_wrapper']);

$subPanels=[
    Html::tag('div','Settings',['class'=>'mn_panel_title']),
    Html::tag('div',$div4,['class'=>'mn_panel_body mn_scrollbar']),
    $this->render('_generalsettingssubpanel',['default_theme'=>$default_theme,'themes'=>$themes,'aut'=>$aut,'webName'=>$webName,'ua_analytics_code'=>$ua_analytics_code,'gmap_api_key'=>$gmap_api_key]),
    $this->render('_socialsettingssubpanel',['facebook'=>$facebook,'twitter'=>$twitter,'instagram'=>$instagram,'pinterest'=>$pinterest,'youtube'=>$youtube]),
    $this->render('_contactsettingssubpanel',['email'=>$email,'address'=>$address,'phone'=>$phone,'mobile_phone'=>$mobile_phone,'opening_hours'=>$opening_hours]),
    $this->render('_generalthemesubpanel',['default_theme'=>$default_theme,'themes'=>$themes]),
    $this->render('_blocksubpanel',['blocks'=>$blocks]),
    $this->render('_myaccountsubpanel',['model'=>$curUser,'langs'=>$langs]),
    $this->render('_langsubpanel',['all_langs'=>$all_langs,'default_lang'=>$default_lang,'available_langs'=>$available_langs]),
    $this->render('_supervisor',['messages'=>$supervisorMessages])
];

$div=Html::tag('div',implode("",$subPanels),['class'=>'mn_float_panel','id'=>'subPanels']);
$superDiv=Html::tag('div',$div,['class'=>'mn_dropdown mn_btn  mn_tb_icon tb_config','title'=>'General configuration']);

echo $superDiv;
