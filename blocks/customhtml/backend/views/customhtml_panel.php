<?php use common\components\Html; ?>
<div id="proBox-customhtml">
    <div class="proBoxTitle">
        Custom Html
    </div>

    <div class="row">
        <div class="col-sm-9">
            <textarea id="customhtml_code" class="customhtml_textarea form-control" rows="15"></textarea>
        </div>
        <div class="col-sm-3">
            <ul class="list-group">
                <li class="list-group-item"><label
                        for="customhtml_purify"><?php echo Yii::t('blocks/customhtml', 'Purify'); ?></label>

                    <div class="onoffswitch pull-right"><input type="checkbox" id="customhtml_purify" class="customhtml_purify onoffswitch-checkbox"
                                                               name="onoffswitch" value="1" checked="checked"><label
                            class="onoffswitch-label" for="customhtml_purify"></label></div>
                    <div class="mn_tip">
                        Enabled cleans html code and removes object and javascript elements.
                    </div>
                </li>

            </ul>
        </div>
    </div>
    <div class="row eRow saveButtonsRow">
        <span class="btn btn-danger toTrash"><i class="fa fa-trash"></i></span>
        <span id="save-customhtml" class="btn btn-success pull-right proBox-save"><i class="fa fa-2x fa-check"></i></span>
    </div>
</div>
