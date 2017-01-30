<?php


use common\components\Html;
use yii\widgets\DetailView;
use common\models\Language;


$opt=Html::ul($lang,[
    'name'=>'selLang',
    'id'=>'selLang',
    'class'=>'list-group selectLang ',
    'item'=>function($item,$index) use ($cur){


        $img=Html::tag('div','',['class'=>'flag flag_'.$item['iso_code']]);
        $label=Html::label($img.' '.$item['name'],'lang_'.$item['id_lang']);
        $html= Html::tag('li',
            Html::radio('select-lang',$item['id_lang']==$cur->id_lang?true:false,
                [
                    'id'=>'lang_'.$item['id_lang'],
                    'value'=>$item['id_lang'],
                    'class'=>'langs',
                    'data-info'=>$item['iso_code']




                ] //Radio html options
            ).$label
            ,
            ['class'=>'list-group-item']);
        return $html;
    }
]);
$div4=Html::tag('div',$opt,['class'=>'mn_panel_wrapper']);

$content=[
    Html::tag('div','Language',['class'=>'mn_panel_title']),
    Html::tag('div',$div4,['class'=>'mn_panel_body'])
];
$div=Html::tag('div',implode("",$content),['class'=>'mn_float_panel','id'=>'lang_subpanel']);
$trnsltd=Html::ul([],['class'=>'translated_langs_ul','id'=>'translated_langs_list']);

$superDiv=Html::tag('div',$div,['class'=>'flag flag_'.$cur->iso_code.' mn_dropdown','title'=>'Lang selection','id'=>'selectLang']);

$col1=Html::tag('div',$superDiv,['class'=>'active-lang-wrapper']);
$col2=Html::tag('div',$trnsltd,['class'=>'content-translations-wrapper']);
$divCont=Html::tag('div',$col1.$col2,['class'=>'langs-wrapper']);
echo $divCont;