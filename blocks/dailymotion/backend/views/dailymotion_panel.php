<div id="proBox-dailymotion">
    <div class="proBoxTitle">
        <?php echo Yii::t('blocks/dailymotion', 'Dailymotion video');?>
    </div>
    <div class="row">
        <div class="col-xs-8">
            <div class="input-group">
                <span class="input-group-addon"><?php echo Yii::t('blocks/dailymotion', 'Url');?>:</span>
                <input type="text" class="form-control" id="dailymotion_url">
            </div>
            <input type="hidden" class="form-control" id="dailymotion_src">
        </div>
    </div>
    <hr>
    <div class="row">
        <div id="dailymotion_item" class="col-xs-12 eVideoContainer">

        </div>
    </div>
    <div class="row eRow saveButtonsRow">
        <span class="btn btn-danger toTrash"><i class="fa fa-trash"></i></span>
        <span class="btn btn-success pull-right proBox-save"><i class="fa fa-2x fa-check"></i></span>
    </div>
</div>