<?php

use common\components\Html;

$br="<br>";
$opt='';
$i=Html::tag('i','',['class'=>'glyphicon glyphicon-chevron-left']);
$icon=Html::tag('i','',['class'=>'glyphicon glyphicon-ok']);
$iconR=Html::tag('i','',['class'=>'glyphicon glyphicon-remove']);
$iconT=Html::tag('i','',['class'=>'glyphicon glyphicon-trash']);
$iplus=Html::tag('i','',['class'=>'glyphicon glyphicon-plus']);

$btnAtras=[
    Html::a($i.Yii::t('app', 'Back'),'#',['class'=>'btn btn-default pull-right btnAtras']),
    $br,
    $br
];
$btnAdd=Html::a($iplus.Yii::t('app', 'Add language'),'#',['id'=>'btnAddLang','class'=>'btn btn-default pull-left']);
$opt.=$btnAdd;
$opt.= implode("",$btnAtras);


$addLang=Html::dropDownList('addLangSelect',null,$available_langs,['class'=>'form-control','id'=>'addLangSelect']);
$addLangBtn=Html::a(Yii::t('app', 'Add Language'),'#',['class'=>'btn btn-default mn_ajax pull-right','data-action'=>'language/addlang','data-callback'=>'cb_addlang','data-target'=>'','id'=>'addLangBtn']);
$dv=Html::tag('div',$addLang.$addLangBtn,['id'=>'add_lang_form','class'=>'hidden addLangDiv']);
$alertAlreadyInstalled=Html::tag('div',Yii::t('app', 'Language already installed'),['class'=>'alert alert-warning hidden lang_alert','id'=>'alert_already_installed']);
$alertSuccesfullyInstalled=Html::tag('div',Yii::t('app', 'Language has been successfully installed'),['class'=>'alert alert-success hidden lang_alert','id'=>'alert_successfully_installed']);
$alertInstallError=Html::tag('div',Yii::t('app', 'An error occurred while installing the language'),['class'=>'alert alert-danger hidden lang_alert','id'=>'alert_install_error']);
$opt.= $dv.$alertAlreadyInstalled.$alertSuccesfullyInstalled.$alertInstallError;


$opt.=Html::ul($all_langs,[
    'name'=>'lang_list',
    'id'=>'lang_list',
    'class'=>'list-group',

    'item'=>function($item,$index) use ($default_lang,$icon,$iconR,$iconT){

        $label=Html::label($item->name,'setlang_'.$item->id_lang,['class'=>'eSimple_label']);


        if($item->id_lang!=$default_lang) {
            if($item->active){
                $b = Html::input('checkbox', 'active_lang', true,['id'=>'setlang_'.$item->id_lang,'class' => 'mn_ajax onoffswitch-checkbox','checked' => '', 'data-action' => 'language/toggleactive', 'data-info' => array('id' => $item->id_lang),'data-callback'=>'cb_toggleactivelang','data-target'=>'']);
            }else{
                $b = Html::input('checkbox', 'active_lang', true,['id'=>'setlang_'.$item->id_lang,'class' => 'mn_ajax onoffswitch-checkbox', 'data-action' => 'language/toggleactive', 'data-info' => array('id' => $item->id_lang),'data-callback'=>'cb_toggleactivelang','data-target'=>'']);
            }
			$d=Html::tag('span',$iconT,['class'=>'mn_ajax btn btn-xs btn-danger langtrash-pull','data-action'=>'language/delete','data-info'=>array('id'=>$item->id_lang),'data-callback'=>'cb_deletelang','data-target'=>'','data-beforesend'=>'']);
            $l=Html::label ('','setlang_'.$item->id_lang,['class'=>'onoffswitch-label']);
            $class='onoffswitch pull-right';

        }else{
            $b=' (default)';
            $l='';
			$d='';
            $class='lsp_default_lang';
        }

        $cont=Html::tag('div',$b.$l,['class'=>$class]);
        $html= Html::tag('li',$d.$label.$cont,
            ['class'=>'list-group-item','id'=>'lang_item_'.$item->id_lang]);
        return $html;
    }
]);

$content=[
    $opt
];
$wrapper=Html::tag('div',implode("",$content),['class'=>"mn_panel_wrapper"]);

$div1=Html::tag('div',$wrapper,['class'=>'mn_scrollbar sp_with_scrollbar']);
$div=Html::tag('div',$div1,['class'=>'hidden sub_panel withScrollbar','title'=>Yii::t('app', 'Languages'),'id'=>'sp_language_settings']);

echo $div;