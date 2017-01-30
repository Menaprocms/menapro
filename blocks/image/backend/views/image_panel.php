<?php use common\components\Html;
use yii\helpers\src;

?>
<div id="proBox-image">
    <div class="proBoxTitle">
        <span id="imageTitle" class=""><?php echo Yii::t('blocks/image', 'Image');?></span>
    </div>
    <div class="row">

        <div class="col-sm-5">
            <div id="image_previewImage"></div>
        </div>
        <div class="col-sm-6">
            <div class="row">
                <div class="col-sm-12">
                    <label for="image_alt"><?php echo Yii::t('blocks/image', 'ALT');?>:</label>
                    <input type="text" class="form-control" id="image_alt"/>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <label for="image_src"><?php echo Yii::t('blocks/image', 'URL');?>:</label>

                    <div class="input-group">
                        <input type="text" class="form-control" id="image_src" placeholder="URL"/>
                        <span class="input-group-btn">
                          <button class="btn btn-default" id="image_browse" type="button"><?php echo Yii::t('blocks/image', 'Browse');?></button>
                         </span>
                    </div>
                </div>
                <div class="col-sm-6">
                    <label for="image_elink"><?php echo Yii::t('blocks/image', 'LINK');?>:</label>
                    <div id="image_elink" class="image_elink"></div>
                </div>

            </div>
        </div>
    </div>
    <div class="row eRow saveButtonsRow">
        <span class="btn btn-danger toTrash"><i class="fa fa-trash"></i></span>
        <span class="btn btn-success pull-right proBox-save" id="save-image"><i class="fa fa-2x fa-check"></i></span>
    </div>
</div>
