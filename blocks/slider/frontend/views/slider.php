<?php
use common\components\Html;

?>
<div class="eSlider">

    <?php

    echo Html::ul($col->content->slides,[
        "item"=>function($item,$index) use ($col) {
            $image=Html::thumbnail($item->src,round((1200*($col->class/12))),null);
            if(isset($item->elink)){
                    return Html::tag("li",Html::elink(Html::img($image,['alt'=>$item->alt]),$item->elink));
            }else{
                return Html::tag("li",Html::img($image,['alt'=>$item->alt]));
            }

        },
        "class"=>"eSliderBx"
    ])
    ?>
</div>