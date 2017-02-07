<?php


use common\components\Html;
use yii\widgets\DetailView;
use common\models\Configuration;
$br="<br>";
$opt='';
$i=Html::tag('i','',['class'=>'glyphicon glyphicon-chevron-left']);
$ir = Html::tag('i', '', ['class' => 'glyphicon glyphicon-chevron-right pull-right']);

$btnAtras=[
    Html::a($i.Yii::t('app', 'Back'),'#',['class'=>'btn btn-default pull-right btnAtras']),
    $br,
    $br
];
$opt.= implode("",$btnAtras);

$facebookAccount=isset($facebook)?$facebook:'';

$facebookContent=[
    Html::label (Yii::t('app', 'Facebook account'),'social_facebook',['class'=>'page_settings_label']),
    $br,
    Html::input ('text','social_facebook',$facebookAccount,['id'=>'social_facebook','class'=>'mn_ajax','data-action'=>'configuration/facebook','data-callback'=>'','data-target'=>'','data-currentval'=>$facebookAccount])
];

$twitterAccount=isset($twitter)?$twitter:'';

$twitterContent=[
    Html::label (Yii::t('app', 'Twitter account'),'social_twitter',['class'=>'page_settings_label']),
    $br,
    Html::input ('text','social_twitter',$twitterAccount,['id'=>'social_twitter','class'=>'mn_ajax','data-action'=>'configuration/twitter','data-callback'=>'','data-target'=>'','data-currentval'=>$twitterAccount])
];

$instagramAccount=isset($instagram)?$instagram:'';

$instagramContent=[
    Html::label (Yii::t('app', 'Instagram account'),'social_instagram',['class'=>'page_settings_label']),
    $br,
    Html::input ('text','social_instagram',$instagramAccount,['id'=>'social_instagram','class'=>'mn_ajax','data-action'=>'configuration/instagram','data-callback'=>'','data-target'=>'','data-currentval'=>$instagramAccount])
];

$pinterestAccount=isset($pinterest)?$pinterest:'';

$pinterestContent=[
    Html::label (Yii::t('app', 'Pinterest account'),'social_pinterest',['class'=>'page_settings_label']),
    $br,
    Html::input ('text','social_pinterest',$pinterestAccount,['id'=>'social_pinterest','class'=>'mn_ajax','data-action'=>'configuration/pinterest','data-callback'=>'','data-target'=>'','data-currentval'=>$pinterestAccount])
];

$youtubeAccount=isset($youtube)?$youtube:'';

$youtubeContent=[
    Html::label (Yii::t('app', 'Youtube account'),'social_youtube',['class'=>'page_settings_label']),
    $br,
    Html::input ('text','social_youtube',$youtubeAccount,['id'=>'social_youtube','class'=>'mn_ajax','data-action'=>'configuration/youtube','data-callback'=>'','data-target'=>'','data-currentval'=>$youtubeAccount])
];
$liYoutube=Html::tag('li',implode("",$youtubeContent),['class'=>'list-group-item']);

$socialSettingsContent=[
    Html::tag('li',implode("",$facebookContent),['class'=>'list-group-item']),
    Html::tag('li',implode("",$twitterContent),['class'=>'list-group-item']),
    Html::tag('li',implode("",$instagramContent),['class'=>'list-group-item']),
    Html::tag('li',implode("",$pinterestContent),['class'=>'list-group-item']),
    Html::tag('li',implode("",$youtubeContent),['class'=>'list-group-item'])
];

$content=[
    Html::tag('div',Yii::t('app', 'Social settings'),['class'=>'mn_subpanel_title']),
    $opt,
    Html::tag('ul',implode("",$socialSettingsContent),['class'=>'list-group'])
];

$wrapper=Html::tag('div',implode("",$content),['class'=>"mn_panel_wrapper"]);

$div=Html::tag('div',$wrapper,['class'=>'hidden sub_panel','title'=>Yii::t('app', 'Social'),'id'=>'sp_social_settings']);

echo $div;