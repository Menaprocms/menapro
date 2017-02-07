<?php
use common\components\Html;

if(isset($col->content->title)){
     ?>
          <h3><?php echo $col->content->title?></h3>
      <?php

  }
    echo Html::ul($col->content->items,[
        "class"=>"eList",
        "item"=>function($item,$index){
            if(isset($item->eicon)){
                $iconClass=$item->eicon;
            }else{
                $iconClass='fa fa-circle';
            }
            $icon=Html::tag('i','',['class'=>$iconClass]);

            return Html::tag('li',Html::elink($icon.$item->text,$item->elink));

        },

    ])
  ?>


