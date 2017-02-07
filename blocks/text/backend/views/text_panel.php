<div id="proBox-text">
    <div class="proBoxTitle">
        <?php echo Yii::t('blocks/text','Edición texto'); ?>
    </div>
    <div class="row">
        <div class="col-xs-12">
                <div class="row">
                    <div class="col-sm-9">
                        <div id="text_editor">

                        </div>
                    </div>
                    <div class="col-sm-3 editor_options">
                        <div id="tinymenu"></div>
                        <ul class="editor-formats">
                            <li data-format="H1" data-selector="H1" data-group="common"><h1><?php echo Yii::t('blocks/text','Title'); ?> </h1></li>
                            <li data-format="H2" data-selector="H2" data-group="common"><h2><?php echo Yii::t('blocks/text','SubTitle'); ?></h2></li>
                            <li data-format="H3" data-selector="H3" data-group="common"><h3><?php echo Yii::t('blocks/text','Chapter'); ?></h3></li>
                            <li data-format="H4" data-selector="H4" data-group="common"><h4><?php echo Yii::t('blocks/text','SubChapter'); ?></h4></li>
                            <li data-format="H5" data-selector="H5" data-group="common"><h5><?php echo Yii::t('blocks/text','Part'); ?></h5></li>
                            <li data-format="H6" data-selector="H6" data-group="common"><h6><?php echo Yii::t('blocks/text','SubPart'); ?></h6></li>
                            <li data-format="P" data-selector="P"  data-group="common"><p><?php echo Yii::t('blocks/text','Paragraph'); ?></p></li>
                            <li data-format="BLOCKQUOTE" data-selector="BLOCKQUOTE"  data-group="common"><p><?php echo Yii::t('blocks/text','Blockquote'); ?></p></li>
                        </ul>
                        <ul class="editor-formats" data-group="headers">
                            <li data-format="insertunorderedlist" data-selector="UL"  data-group="list"><?php echo Yii::t('blocks/text','Unordered list'); ?></li>
                            <li data-format="insertorderedlist" data-selector="OL"  data-group="list"><?php echo Yii::t('blocks/text','Ordered list'); ?></li>
                        </ul>

                    </div>
                </div>

        </div>
    </div>
    <div class="row eRow saveButtonsRow">
        <span class="btn btn-danger toTrash"><i class="fa fa-trash"></i></span>
        <span class="btn btn-success pull-right proBox-save"><i class="fa fa-2x fa-check"></i></span>
    </div>


    <input type="hidden" id="text-FILE_selection">
</div>