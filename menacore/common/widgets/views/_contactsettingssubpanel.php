<?php

use common\components\Html;
use yii\widgets\DetailView;
use common\models\Configuration;
$opt='';
$i=Html::tag('i','',['class'=>'glyphicon glyphicon-chevron-left']);
$ir = Html::tag('i', '', ['class' => 'glyphicon glyphicon-chevron-right pull-right']);
$br="<br>";
$btnAtras=[
    Html::a($i.Yii::t('app', 'Back'),'#',['class'=>'btn btn-default pull-right btnAtras']),
    $br,
    $br
];
$opt.= implode("",$btnAtras);


$email_val=isset($email)?$email:'';
$emailContent=[
    Html::label (Yii::t('app', 'Email account'),'contact_email',['class'=>'page_settings_label']),
    $br,
    Html::input ('text','contact_email',$email_val,['id'=>'contact_email','class'=>'mn_ajax','data-action'=>'configuration/email','data-target'=>'','data-currentval'=>$email_val]),
];

$address_val=isset($address)?$address:'';
$addressContent=[
    Html::label (Yii::t('app', 'Address'),'contact_address',['class'=>'page_settings_label']),
    $br,
    Html::input ('text','contact_address',$address_val,['id'=>'contact_address','class'=>'mn_ajax','data-action'=>'configuration/address','data-target'=>'','data-currentval'=>$address_val])
];

$phone_val=isset($phone)?$phone:'';
$phoneContent=[
    Html::label (Yii::t('app', 'Phone'),'contact_phone',['class'=>'page_settings_label']),
    $br,
    Html::input ('text','contact_phone',$phone_val,['id'=>'contact_phone','class'=>'mn_ajax','data-action'=>'configuration/phone','data-target'=>'','data-currentval'=>$phone_val])
];

$mobilephone_val=isset($mobile_phone)?$mobile_phone:'';
$mobilephoneContent=[
    Html::label (Yii::t('app', 'Mobile phone'),'contact_mobilephone',['class'=>'page_settings_label']),
    $br,
    Html::input ('text','contact_mobilephone',$mobilephone_val,['id'=>'contact_mobilephone','class'=>'mn_ajax','data-action'=>'configuration/mobilephone','data-target'=>'','data-currentval'=>$mobilephone_val])
];

$openinghours_val=isset($opening_hours)?$opening_hours:'';
$openinghoursContent=[
    Html::label (Yii::t('app', 'Opening hours'),'contact_openinghours',['class'=>'page_settings_label']),
    $br,
    Html::input ('text','contact_openinghours',$openinghours_val,['id'=>'contact_openinghours','class'=>'mn_ajax','data-action'=>'configuration/openinghours','data-target'=>'','data-currentval'=>$openinghours_val])
];

$contactSettingsTabs=[
    Html::tag('li',implode("",$emailContent),['class'=>'list-group-item']),
    Html::tag('li',implode("",$addressContent),['class'=>'list-group-item']),
    Html::tag('li',implode("",$phoneContent),['class'=>'list-group-item']),
    Html::tag('li',implode("",$mobilephoneContent),['class'=>'list-group-item']),
    Html::tag('li',implode("",$openinghoursContent),['class'=>'list-group-item'])
];


$contactSettingsContent=[
    //@todo: Los títulos internos de los subpaneles pasan a ser mn_subpanel_title
    Html::tag('div',Yii::t('app', 'Contact settings'),['class'=>'mn_subpanel_title']),
    $opt,
    Html::tag('ul',implode("",$contactSettingsTabs),['class'=>'list-group'])
];


$wrapper=Html::tag('div',implode("",$contactSettingsContent),['class'=>"mn_panel_wrapper"]);
$div=Html::tag('div',$wrapper,['class'=>'hidden sub_panel','title'=>Yii::t('app', 'Contact'),'id'=>'sp_contact_settings']);

echo $div;