<div id="proBox-youtube">
    <div class="proBoxTitle">
        <?php echo Yii::t('blocks/youtube', 'Youtube video');?>
    </div>
    <div class="row">
        <div class="col-xs-8">
            <div class="input-group">
                <span class="input-group-addon"><?php echo Yii::t('blocks/youtube', 'Url');?>:</span>
                <input type="text" class="form-control" id="youtube_url">
            </div>
            <input type="hidden" class="form-control" id="youtube_src">
        </div>
    </div>
    <hr>
    <div class="row">
        <div id="youtube_item" class="col-xs-12 eVideoContainer">

        </div>
    </div>
    <div class="row eRow saveButtonsRow">
        <span class="btn btn-danger toTrash"><i class="fa fa-trash"></i></span>
        <span class="btn btn-success pull-right proBox-save"><i class="fa fa-2x fa-check"></i></span>
    </div>
</div>