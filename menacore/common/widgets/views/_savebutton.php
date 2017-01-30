<?php


use common\components\Html;
use yii\widgets\DetailView;
use common\models\Configuration;

$html='';
$auto=Configuration::findOne(['name'=>'_AUTOSAVE_']);

if($auto->value=='0'){
    $clss='';


}else{
    $clss='hidden';
}
$a=Html::a('','#',['class'=>'','id'=>'submitAddcms','name'=>'submitAddcms']);
$html.=Html::tag('div',$a,['class'=>'mn_tb_icon mn_btn tb_save '.$clss,'id'=>'showSaveButton']);
echo $html;