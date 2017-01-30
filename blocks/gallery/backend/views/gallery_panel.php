<?php use common\components\Html;
use yii\helpers\src;

?>
<div id="proBox-gallery">
    <div class="proBoxTitle">
        <span id="galleryTitle" class=""><?php echo Yii::t('blocks/gallery', 'Gallery');?></span>
    </div>
    <div class="row">

        <div class="col-sm-12">
            <span class="btn btn-info"
                  id="gallery_browse"><i class="fa fa-search"></i> </span>
        </div>
    </div>
    <ul class="list-group" id="gallery_images">

    </ul>
    <div class="row eRow saveButtonsRow">
        <span class="btn btn-danger toTrash"><i class="fa fa-trash"></i></span>
        <span class="btn btn-success pull-right proBox-save" id="save-gallery"><i
                class="fa fa-2x fa-check"></i></span>
    </div>
</div>
