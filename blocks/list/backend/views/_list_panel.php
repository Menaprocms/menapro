<?php use common\components\Html; ?>
<div id="proBox-list">
    <div class="proBoxTitle">
        Edición lista
    </div>
    <div class="eContainer eListEditPanel">
        <div class="row eRow ">
            <div class="col-xs-12 eCol">

                <div class="row">
                    <div class="col-xs-12">
                        <div class="input-group">
                            <span class="input-group-addon"><?php echo Yii::t('blocks/list', 'Title');?>:</span>
                            <input type="text" class="form-control" id="list-title">
                        </div>
                        <span class="eHint"><?php echo Yii::t('blocks/list', 'Title of list. Leave empty to disable.');?></span>

                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-xs-5">

                        <div class="input-group">
                            <span class="input-group-addon"><?php echo Yii::t('blocks/list', 'Item');?>:</span>
                            <input type="text" class="form-control" id="txt-list-item">
                        </div>
                        <span class="eHint"><?php echo Yii::t('blocks/list', 'Text of item and link.');?></span>


                    </div>

                    <div class="col-xs-3">
                        <?php
                        echo Html::advancedLink('list');
                        ?>
                        <span class="eHint"><?php echo Yii::t('blocks/list', 'URL.');?></span>

                    </div>
                    <div class="col-xs-2">
                        <?php
                        echo Html::advancedIcon('list');
                        ?>
                    </div>
                    <div class="col-xs-2">
                        <span id="list-item-add" class="btn btn-default"><?php echo Yii::t('blocks/list', 'Add')?></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row eRow ">
            <div class="row">
                <div class="eListEditShadow"></div>
                <div id="list" class="eListEdit col-xs-12">
                    <ul id="ul-list" class="list-group">
                    </ul>
                </div>
            </div>
        </div>
        <div class="row eRow saveButtonsRow">
            <span class="btn btn-danger toTrash"><i class="icon icon-trash"></i></span>
            <span class="btn btn-success pull-right proBox-save"><i class="icon icon-2x icon-check"></i></span>
        </div>
    </div>
</div>
<!-- Clonable -->
<li id="clonable-list-item" class="list-group-item hidden">
    <div class="row">
        <div class="col-xs-1 eHandler">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <div class="col-xs-1">

         <span class="eListIcon"><i class="fa fa-circle"></i></span>
        </div>

        <div class="col-xs-8 item">
                                    <span class="eListEditLink "><a target="_blank" href="#"><i class="fa fa-external-link"></i>
                                        </a></span>
        </div>
        <div class="col-xs-1 btnEdit"><i class="btn btn-default fa fa-edit"></i></div>
        <div class="col-xs-1 btnTrash"><i class="btn btn-default fa fa-trash"></i></div>
    </div>
</li>