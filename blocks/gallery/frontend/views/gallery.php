
<?php
/* @var $this Yii\web\view */
use common\components\Html;
$this->registerCssFile("@web/blocks/gallery/frontend/js/fancybox/jquery.fancybox.css",[]);
$this->registerJsFile("@web/blocks/gallery/frontend/js/fancybox/jquery.fancybox.js",['depends'=>[
    'frontend\assets\JqueryAsset',

]],
    "fancybox");
$this->registerJs('$(".eFancyGallery").fancybox({
  helpers: {
    overlay: {
      locked: false
    }
  }
});',$this::POS_READY)
?>
<div class="eGallery">
    <ul>

        <?php foreach ($col->content->images as $img) { ?>
            <li>
                <?php

                //            $image= Html::img($img->src,['alt' => $img->alt,'class'=>'img-thumbnail']);
                echo Html::a("", $img->src, [
                    'style' => "background-image:url('" . Html::thumbnail($img->src,200,200) . "')",
                    'title' => $img->alt,
                    'class' => 'eFancyGallery',
                    'rel' => 'gallery_' . $cRow . '_' . $cCol]);
                ?>
            </li>
        <?php } ?>
    </ul>
</div>