<?php use common\components\Html; ?>
<div id="proBox-list">
    <div class="proBoxTitle">
        <?php echo Yii::t('blocks/list', 'Edición lista');?>
    </div>
    <div class="eContainer eListEditPanel">
        <div class="row eRow ">
            <div class="col-xs-12 eCol">

                <div class="row">
                    <div class="col-xs-12">
                        <div class="input-group">
                            <span class="input-group-addon"><?php echo Yii::t('blocks/list', 'Title');?>:</span>
                            <input type="text" class="form-control" id="list_title">
                        </div>
                        <span class="eHint"><?php echo Yii::t('blocks/list', 'Title of list. Leave empty to disable.');?></span>

                    </div>
                </div>
                <hr>
            </div>
        </div>
        <div class="row eRow ">
            <div class="row">
                <div class="eListEditShadow"></div>
                <div  class="eListEdit col-xs-12">
                    <ul id="list_items" class="list-group">

                    </ul>
                    <div class="eListAdd">
                        <span class="fa fa-plus"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row eRow saveButtonsRow">
            <span class="btn btn-danger toTrash"><i class="fa fa-trash"></i></span>
            <span class="btn btn-success pull-right proBox-save"><i class="fa fa-2x fa-check"></i></span>
        </div>
    </div>
</div>
