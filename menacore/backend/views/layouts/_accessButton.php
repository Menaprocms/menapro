<div class="mn_dropdown mn_btn  mn_tb_icon tb_access" title="Access control">

    <div class="mn_float_panel">
        <div class="mn_panel_title"><?php echo Yii::t('app','Access control')?></div>
        <div class="mn_panel_body mn_scrollbar " >
            <div class="mn_panel_wrapper">
                <span class="mn_panel_subtitle"><?php echo Yii::t('app','Groups')?></span>
                            <span class="mn_tip">
                                <?php echo Yii::t('app','Enable or disable access to this page')?>
                            </span>
                <ul class="list-group">
                    <li class="list-group-item">
                    <?php echo Yii::t('app','Cualquiera')?>

                        <div class="onoffswitch pull-right">
                            <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox"
                                   id="myonoffswitch" checked>
                            <label class="onoffswitch-label" for="myonoffswitch"></label>
                        </div>
                    </li>
                    <li class="list-group-item">
                        Alumnos
                        <div class="onoffswitch pull-right">
                            <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox"
                                   id="myonoffswitch2" checked>
                            <label class="onoffswitch-label" for="myonoffswitch2"></label>
                        </div>
                    </li>
                    <li class="list-group-item">
                        Profesores
                        <div class="onoffswitch pull-right">
                            <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox"
                                   id="myonoffswitch3" checked>
                            <label class="onoffswitch-label" for="myonoffswitch3"></label>
                        </div>
                    </li>

                </ul>

            </div>
        </div>
    </div>
</div>