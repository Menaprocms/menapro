<?php use common\components\Html;
use yii\helpers\src;

?>
<div id="proBox-slider">
    <div class="proBoxTitle">
        <span id="sliderTitle" class=""><?php echo Yii::t('blocks/slider', 'Slider'); ?></span>
    </div>
    <div class="row">

        <div class="col-sm-12">
            <span class="btn btn-info"
                  id="slider_browse"><i class="fa fa-search"></i> <?php echo Yii::t('blocks/slider', 'Browse images'); ?></span>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 mn_tip text-center">
            Info: Not all themes supports descriptions.
        </div>
    </div>
    <ul class="list-group" id="slider_slides">

    </ul>
    <div class="row eRow saveButtonsRow">
        <span class="btn btn-danger toTrash"><i class="fa fa-trash"></i></span>
        <span class="btn btn-success pull-right proBox-save" id="save-slider"><i
                class="fa fa-2x fa-check"></i></span>
    </div>
</div>
