<?php


use common\components\Html;
use yii\widgets\DetailView;
use common\models\Content;

$btnText=Yii::t('app','Published');
if($id_item!=null) {
    $model = Content::findOne($id_item);
    $save=true;
}else{
    $save=false;
}

$lbl=Html::label('','myonoffswitch15',['class'=>'onoffswitch-label']);

if($save) {
    if ($model['active'] === 1) {
        $input = Html::input('checkbox', 'onoffswitch', true, ['id' => 'myonoffswitch15', 'class' => 'onoffswitch-checkbox mn_ajax', 'checked' => '','data-action'=>'content/togglevisible','data-info'=>array('id'=>$id_item),'data-callback'=>"cb_active",'data-target'=>'']);
    } else {
        $input = Html::input('checkbox', 'onoffswitch', true, ['id' => 'myonoffswitch15', 'class' => 'onoffswitch-checkbox mn_ajax','data-action'=>'content/togglevisible','data-info'=>array('id'=>$id_item),'data-callback'=>"cb_active",'data-target'=>'']);
    }
}else{
    $input = Html::input('checkbox', 'onoffswitch', true, ['id' => 'myonoffswitch15', 'class' => 'onoffswitch-checkbox mn_ajax', 'checked' => '','data-action'=>'content/togglevisible','data-info'=>array('id'=>$id_item),'data-callback'=>"cb_active",'data-target'=>'']);
}



$div=Html::tag('div',$input.$lbl,['class'=>'onoffswitch']);
$superDiv=Html::tag('div',$btnText.$div,['class'=>'tb_published']);
echo $superDiv;