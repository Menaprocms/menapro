<?php

use common\components\Html;
use common\widgets\Elink;

echo Html::beginTag('div',['class'=>'eIcon']);

Elink::begin([
    'linkData'=>$col->content->elink
]);
echo Html::tag('span',Html::tag('i','',['class'=>$col->content->eicon]),['class'=>'eIcon-icon']);

if(isset($col->content->title)){
    echo Html::tag('h3',$col->content->title,['class'=>'eIcon-title']);
}

if(isset($col->content->text)){
    echo Html::tag('span',$col->content->text,['class'=>'eIcon-text']);
}
Elink::end();
echo Html::endTag('div');
?>
