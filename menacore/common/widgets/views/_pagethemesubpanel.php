<?php


use common\components\Html;
use yii\widgets\DetailView;
use common\models\Configuration;


$br="<br>";
$opt='';
$i=Html::tag('i','',['class'=>'glyphicon glyphicon-chevron-left']);


$btnAtras=[
    Html::a($i.Yii::t('app', 'Back'),'#',['class'=>'btn btn-default pull-right btnAtras']),
    $br,
    $br
];
$opt.= implode("",$btnAtras);

$opt.=Html::ul($themes,[
    'name'=>'config',
    'class'=>'list-group',
    'item'=>function($item,$index) use ($page_theme,$model,$general_theme){


        $label=Html::label($index==$general_theme?'default('.$item.')':$item,'theme_'.$index);
        //@todo->si es default theme cambio el index:
        if($index==$general_theme){
            $index='default';
        }

        $html= Html::tag('li',
            Html::radio('select-page-theme',$index==$page_theme?true:false,
                [
                    'id'=>'theme_'.($index=='default'?$general_theme:$index),
                    'value'=>$index==$general_theme?'default':$index,
                    'class'=>'mn_ajax',
                    'data-action'=>'content/pagetheme',
                    'data-info'=>array('id'=>$model->id),
                    'data-callback'=>'cb_pagetheme',
                ] //Radio html options
            ).$label
            ,
            ['class'=>'list-group-item']);
        return $html;
    }
]);

$content=[
    Html::tag('div',Yii::t('app', 'Page theme settings'),['class'=>'mn_subpanel_title']),
    $opt
];

$wrapper=Html::tag('div',implode("",$content),['class'=>"mn_panel_wrapper"]);
$div1=Html::tag('div',$wrapper,['class'=>'mn_scrollbar sp_with_scrollbar']);
$div=Html::tag('div',$div1,['class'=>'hidden sub_panel withScrollbar','title'=>Yii::t('app', 'Page theme'),'id'=>'sp_theme']);

echo $div;
