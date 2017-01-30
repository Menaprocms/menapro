<?php

use common\components\Html;
use yii\widgets\DetailView;
use common\models\Configuration;
$opt='';
$i=Html::tag('i','',['class'=>'glyphicon glyphicon-chevron-left']);
$btnAtras=Html::a($i.Yii::t('app', 'Back'),'#',['class'=>'btn btn-default pull-right btnAtras']);
$opt.= $btnAtras.'<br><br>';


if($hasChilds){
    $i=Html::tag('i','',['class'=>'icon icon-warning-sign']);
    $msg=Html::tag('p',$i.' '.Yii::t('app', 'The page can´t be deleted because it has other pages associated'));
    $col=Html::tag('div',$msg,['class'=>'col-md-10 col-md-offset-1 alert alert-danger']);
    $div=Html::tag('div',$col,['class'=>'row']);
    $opt.=$div;
}else{

    $iOk=Html::tag('i','',['class'=>'icon icon-ok']);
    $iKo=Html::tag('i','',['class'=>'icon icon-remove']);
    $msg=Html::tag('p',Yii::t('app', 'Do you really want to delete this page?'));
    $col=Html::tag('div',$msg,['class'=>'col-md-12']);
    $div=Html::tag('div',$col,['class'=>'row']);
    $opt.=$div;
    //@todo: ¿Por qué si estoy escribiendo en un string no me toma como tal el { ?
    $cb="cb_del_page";
    $btnok=Html::a($iOk.'YES','#',['class'=>'btn btn-success pull-left delPageConfirmBtn mn_ajax','id'=>'delpage_ok','data-action'=>'content/delete','data-info'=>array('id'=>$model->id),'data-callback'=>$cb,'data-target'=>'']);
    $btnno=Html::a($iKo.'NO','#',['class'=>'btn btn-danger pull-right delPageConfirmBtn','id'=>'delpage_no']);
    $colok=Html::tag('div',$btnok,['class'=>'col-md-6 text-center']);
    $colno=Html::tag('div',$btnno,['class'=>'col-md-6 text-center']);
    $row=Html::tag('div',$colok.$colno,['class'=>'row']);

    $opt.=$row;
}

$divTitle=Html::tag('div',Yii::t('app', 'Delete page'),['class'=>'mn_subpanel_title']);
$wrapper = Html::tag('div', $divTitle.$opt, ['class' => "mn_panel_wrapper"]);

$div=Html::tag('div',$wrapper,['class'=>'hidden sub_panel','title'=>Yii::t('app', 'Delete'),'id'=>'sp_delete_page_confirm']);

echo $div;