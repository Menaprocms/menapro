<?php use common\components\Html; ?>
<div id="proBox-icon">
    <div class="proBoxTitle">
        <?php echo Yii::t('blocks/icon', 'Icon');?>
    </div>
    <div class="row">
        <div class="col-xs-8 col-xs-offset-2">
            <div class="row">
                <div class="col-xs-2">
                    <div id="icon_eicon" class="icon_helper icon_eicon"></div>
                </div>
                <div class="col-xs-9">
                    <input type="text" class="form-control icon_title" id="icon_title" placeholder="<?php echo Yii::t('blocks/icon', 'Added title to icon (Optional)');?> ">
                </div>
            </div>
            <div class="row">

                <div class="col-xs-9 col-xs-offset-2">
                    <textarea class="form-control icon_text" id="icon_text" maxlength="140" placeholder="<?php echo Yii::t('blocks/icon', 'Added text to icon (Optional)');?> "></textarea>
                </div>

                <div class="col-xs-1">
                    <p id="countChar">140</p>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-9 col-xs-offset-2">
                    <label for="icon_elink"><?php echo Yii::t('blocks/icon', 'LINK');?>:</label>
                    <div id="icon_elink" class="icon_elink"></div>
                </div>
            </div>
        </div>
    </div>


    <hr>
    <div class="row eRow saveButtonsRow">
        <span class="btn btn-danger toTrash"><i class="fa fa-trash"></i></span>
        <span class="btn btn-success pull-right proBox-save"><i class="fa fa-2x fa-check"></i></span>
    </div>
</div>
<!-- Clonable -->
<div id="clonable-icon-item" class="hidden eIcon">
    <a  href="#" target="_blank">
        <span>
            <i class="eIcon_icon fa fa-4x"></i>
        </span>
        <br>
        <h3 class="eIcon_title"></h3>
        <span class="eIcon_text"></span>
    </a>
</div>
<div id="clonable-icon-item-preview" class="hidden eIcon">
    <div class="row">
        <div class="col-xs-12">
            <i class="eIcon_icon fa fa-3x"></i>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <span class="eIcon_text"></span>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <a href="#"><i class="fa fa-link"><span class="eIcon_link"></span></i>
            </a>
        </div>
    </div>
</div>