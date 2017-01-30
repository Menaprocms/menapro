<?php

use common\components\Html;

use yii\widgets\DetailView;
use common\models\Configuration;
$br="<br>";
$hr="<hr>";
$opt='';
$opt.=$hr.Html::a( Html::tag('i',"",['class'=>'fa fa-th']) ." ".Yii::t('app', 'Get more blocks'),"http://menapro.com",['class'=>'btn btn-info','target'=>"_blank"]).$hr;

$i=Html::tag('i','',['class'=>'glyphicon glyphicon-chevron-left']);
$icon=Html::tag('i','',['class'=>'glyphicon glyphicon-trash']);
$iplus=Html::tag('i','',['class'=>'glyphicon glyphicon-plus']);

$btnAtras=[
    Html::a($i.Yii::t('app', 'Back'),'#',['class'=>'btn btn-default pull-right btnAtras']),
    $br,
    $br
];
$btnAdd=Html::a($iplus.Yii::t('app', 'Add block'),'#',['id'=>'btnAddBlock','class'=>'btn btn-default pull-left']);
$opt.=$btnAdd;
$opt.= implode("",$btnAtras);

$uploadBlockForm=[
    Html::tag('i','',['class'=>'icon icon-spinner hidden icon-2x','id'=>'loader_block']),
    Html::beginForm(['block/upload'],'post',['enctype'=>'multipart/form-data']),
    Html::fileInput('blockFile',''),
    Html::hiddenInput('name','block'),
    Html::endForm()
];
$dv=Html::tag('div',implode("",$uploadBlockForm),['id'=>'upload_block_form','class'=>'hidden']);
$opt.= $dv;


$opt.=Html::ul($blocks,[
    'name'=>'config',
    'class'=>'list-group',

    'item'=>function($item,$index) use ($icon){
        $v=$item->version;
        $label=Html::label($item->langFields[0]->name.' (v.'.$v.')','block_'.$item->prefix,['class'=>'eSimple_label','title'=>$item->langFields[0]->name.' (v.'.$v.')']);
        $callback_del="$('#block_item_".$item->id."').remove();
                        $('#".$item->prefix."').remove();
                        $('#proBox-".$item->prefix."').remove();";
        $ba="if (confirm('Do you really want to delete this block?')) {
                $(this).prev('span.text').remove();
                    return true;
                }else{
                    return false;
                }";
        $b=!$item->block_default?Html::tag('span',$icon,['class'=>'mn_ajax btn btn-xs btn-danger pull-right','data-action'=>'block/delete','data-info'=>array('id'=>$item->id),'data-callback'=>$callback_del,'data-target'=>'','data-beforesend'=>$ba]):'';

        $html= Html::tag('li',$label.$b,
            ['class'=>'list-group-item','id'=>'block_item_'.$item->id]);
        return $html;
    }
]);
$content=[
    $opt
];

$wrapper=Html::tag('div',implode("",$content),['class'=>"mn_panel_wrapper"]);
$div1=Html::tag('div',$wrapper,['class'=>'mn_scrollbar sp_with_scrollbar']);
$div=Html::tag('div',$div1,['class'=>'hidden sub_panel withScrollbar','title'=>Yii::t('app', 'Blocks'),'id'=>'sp_block_settings']);

echo $div;