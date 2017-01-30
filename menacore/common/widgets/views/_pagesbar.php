<?php

use common\components\Html;
use yii\widgets\DetailView;
use common\models\Content;

$collect='';
$span=Html::tag('span','',['class'=>'mn_new_page']);
$url='#';
$link=Html::a($span, $url);

$n=Html::tag('li',$link,['class'=>'mn_ajax mn_ajax mjs-nestedSortable-leaf','data-action'=>'content/create','data-info'=>'','data-target'=>'','data-beforesend'=>'$(".superMedinapro").addClass("closed")','data-callback'=>'cb_create']);
$collect.=$html;
$ul=Html::tag('ul',$collect,['class'=>'sidebar-nav mn_pages closed mn_animated_childs pagesortable']);
$uln=Html::tag('ul',$n,['class'=>'sidebar-nav mn_pages closed mn_animated_childs mn_new_pages']);
$icon=Html::tag('i','',['class'=>'glyphicon glyphicon-trash']);

$btnTrash=Html::a($icon,'#trash_fancybox',['class'=>'btn btn-default showTrash mn_animated','id'=>'show_trash_btn']);
$liT=Html::tag('li',$btnTrash);
$ulT=Html::tag('ul',$liT,['class'=>'trash_ul']);
$superDiv=Html::tag('div',$uln.$ul.$ulT,['id'=>'sidebar-wrapper','class'=>'mn_scrollbar']);
echo $superDiv;
