<?php


use common\components\Html;
use yii\widgets\DetailView;
use common\models\Configuration;
$br="<br>";
$opt='';
$i=Html::tag('i','',['class'=>'glyphicon glyphicon-chevron-left']);
$iRight=Html::tag('i','',['class'=>'glyphicon glyphicon-chevron-right']);

$iL=Html::tag('i','',['class'=>'fa fa-lock icon_pass_access']);
$iLoader=Html::tag('i','',['class'=>'fa fa-spinner fa-spin hidden','id'=>'unlock_pass_loader']);
$btnAtras=[
    Html::a($i.Yii::t('app', 'Back'),'#',['class'=>'btn btn-default pull-right btnAtras']),
    $br,
    $br
];
$opt.= implode("",$btnAtras);

$btnC=Html::a($iRight,'#',['class'=>'btn btn-info mn_ajax btnGo','data-action'=>'user/unlockfields','data-info'=>'','data-callback'=>'cb_unlockfields','data-target'=>'','data-currentval'=>'','data-beforesend'=>'bs_unlockfields','data-field'=>'#password_access']);
$passwordaccessContent=[
    Html::tag('span',$iL.$iLoader,['class'=>'input-group-addon']),
    Html::input ('password','password_access','',['id'=>'password_access','class'=>'','placeholder'=>' '.Yii::t('app', 'Password')]),
    Html::tag('span',$btnC,['class'=>'input-group-btn'])

];
$passwordaccess=[
    Html::tag('div',implode("",$passwordaccessContent),['class'=>'input-group'])
];

$passFields=[
    Html::label (Yii::t('app', 'New Password'),'new_password',['class'=>'page_settings_label']),
    Html::input ('password','new_password','',['id'=>'new_password','class'=>'']),
    Html::label (Yii::t('app', 'Confirm New Password'),'new_password_confirm',['class'=>'page_settings_label']),
    Html::input ('password','new_password_confirm','',['id'=>'new_password_confirm']),
    Html::tag('span',Yii::t('app', 'The passwords do not match'), ['id'=>'not_match_err','class' => 'mn_tip_error hidden']),
    Html::a(Yii::t('app', 'Change'),'#',['class'=>'btn btn-default change_pass mn_ajax','data-action'=>'user/changepass','data-info'=>array('id'=>$model->id),'data-callback'=>'','data-beforesend'=>'bs_changepass','data-target'=>'','data-currentval'=>'','data-field'=>'#new_password'])
];
$changePass=[
    Html::tag('span',Yii::t('app', 'Change Password'),['class'=>''])
];
/*$cbN="
var un=$('#username').val().charAt(0).toUpperCase() + $('#username').val().substr(1);
$('#logout_btn').html(un+' (".Yii::t('app', 'Logout').")')";*/
$usernameFields=[
    Html::label (Yii::t('app', 'Username'),'username',['class'=>'page_settings_label']),
    Html::input ('text','username',$model->username,['id'=>'username','class'=>'mn_ajax','data-action'=>'user/changeusername','data-info'=>array('id'=>$model->id),'data-callback'=>'cb_changeusername','data-target'=>'','data-currentval'=>$model->username]),
];

$changeUsername=[
    Html::tag('span',Yii::t('app', 'Change Username'),['class'=>''])
];
$emailFields=[
    Html::label (Yii::t('app', 'Email'),'email',['class'=>'page_settings_label']),
    Html::input ('text','email',$model->email,['id'=>'email','class'=>'mn_ajax','data-action'=>'user/changeemail','data-info'=>array('id'=>$model->id),'data-callback'=>'','data-target'=>'','data-currentval'=>$model->email]),
];

$changeEmail=[
    Html::tag('span',Yii::t('app', 'Change Email'),['class'=>'']),
];
$cbLang="location.reload()";
$langFields=[
    Html::label (Yii::t('app', 'Language'),'user_language',['class'=>'page_settings_label']),
    Html::dropDownList('user_language',$model->lang,$langs,['id'=>'user_language','class'=>'mn_ajax form-control','data-action'=>'user/changelang','data-info'=>array('id'=>$model->id),'data-callback'=>$cbLang,'data-target'=>'','data-currentval'=>$model->lang])
];
$changeLang=[
    Html::tag('span',Yii::t('app', 'Change Language'),['class'=>'']),
];

$userSettingsList=[
    Html::tag('li',implode("",$passwordaccess),['class'=>'list-group-item pass_unlock']),
    Html::tag('li',implode("",$changePass),['class'=>'list-group-item select_user_field user_field_locked','data-field'=>'pass']),
    Html::tag('li',implode("",$passFields),['class'=>'list-group-item hidden user_form','id'=>'change_pass_form']),
    Html::tag('li',implode("",$changeUsername),['class'=>'list-group-item select_user_field user_field_locked','data-field'=>'username']),
    Html::tag('li',implode("",$usernameFields),['class'=>'list-group-item hidden user_form','id'=>'change_username_form']),
    Html::tag('li',implode("",$changeEmail),['class'=>'list-group-item select_user_field user_field_locked','data-field'=>'email']),
    Html::tag('li',implode("",$emailFields),['class'=>'list-group-item hidden user_form','id'=>'change_email_form']),
    Html::tag('li',implode("",$changeLang),['class'=>'list-group-item select_user_field user_field_locked','data-field'=>'lang']),
    Html::tag('li',implode("",$langFields),['class'=>'list-group-item hidden user_form','id'=>'change_lang_form']),

];

$content=[
    Html::tag('div',Yii::t('app', 'My Account'),['class'=>'mn_subpanel_title']),
    $opt,
    Html::tag('ul',implode("",$userSettingsList),['class'=>'list-group'])
];
$wrapper=Html::tag('div',implode("",$content),['class'=>"mn_panel_wrapper"]);

$div=Html::tag('div',$wrapper,['class'=>'hidden sub_panel','title'=>Yii::t('app', 'My Account'),'id'=>'sp_myaccount']);
echo $div;