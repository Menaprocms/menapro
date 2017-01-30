<?php

use common\components\Html;
use common\models\Content;

use yii\helpers\Url;
$html='';

if($id_item!=null) {
    $model = Content::findOne($id_item);
    $url= Yii::$app->urlManagerFrontend->createUrl(['content/view','id'=>$id_item]);
    //@todo:Change name  manager folder!!!
    $arr=explode ('/' ,$url);
    foreach($arr as $k=>$v){
        $pos=strpos($v,'manager');
        if($pos!==false){
            $index=$k;
        }
    }
    unset($arr[$index]);
    $url=implode('/',$arr);








    $a = Html::a('','#', ['class' => 'mn_tb_icon mn_btn tb_live_view', 'id' => 'submitLiveview', 'name' => 'submitLiveview','target'=>'_blank','data-url'=>$url,'data-token'=>$userToken]);

    echo $a;
}