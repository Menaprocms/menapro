<?php
use yii\jui\AutoComplete;
?>
<div id="proBox-googlemap">
    <div class="proBoxTitle">
     <?php echo  Yii::t('blocks/googlemap', 'Google Map');?>
    </div>
    <div id="googlemap_form">
        
        <div class="row">
            <div class="col-sm-8">

                <div class="row">
                    <div class="col-xs-12">
                        <label class="" for="googlemap_address"><?php echo  Yii::t('blocks/googlemap', 'Address');?></label>
                        <input type="text" class="form-control" id="googlemap_address">
<!--                        --><?php //echo  AutoComplete::widget([
//                            'id'=>'googlemap_address',
//                            'class'=>'form-control',
//                            'clientOptions' => [
//                                'source' => ['USA', 'RUS'],
//                            ],
//                        ])?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <label class="" for="googlemap_tooltip_text"><?php echo  Yii::t('blocks/googlemap', 'Tooltip text');?></label>
                        <input type="text" class="form-control" id="googlemap_tooltip_text">
                    </div>
                </div>

            </div>
            <div class="col-sm-4">
                <label class="" for=""><?php echo  Yii::t('blocks/googlemap', 'Options');?></label>
                <ul class="list-group">
                    <li class="list-group-item">
                        <label
                            for="googlemap_fit"><?php echo Yii::t('blocks/googlemap', 'Fit in row height'); ?></label>
                        <div class="onoffswitch "><input type="checkbox" id="googlemap_fit"
                                                         class="googlemap_fit onoffswitch-checkbox"
                                                         name="onoffswitch" value="1""><label
                                class="onoffswitch-label" for="googlemap_fit"></label></div>


                    </li>
                    <li class="list-group-item">

                        <label
                            for="googlemap_type"><?php echo Yii::t('blocks/googlemap', 'Sattellite mode'); ?></label>
                        <div class="onoffswitch "><input type="checkbox" id="googlemap_type"
                                                         class="googlemap_type onoffswitch-checkbox"
                                                         name="onoffswitch" value="1" checked="checked"><label
                                class="onoffswitch-label" for="googlemap_type"></label></div>


                    </li>
                </ul>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="col-sm-2">
                <span id="googlemap_advanced" class="btn btn-default"><i class="fa fa-cog"></i> <?php echo  Yii::t('blocks/googlemap', 'Advanced');?></span>

            </div>

            <div class="col-xs-5 coords">
                <div class="input-group">
                    <label class="input-group-addon"><?php echo  Yii::t('blocks/googlemap', 'Latitude');?></label>
                    <input type="text" class="form-control" id="googlemap_latitude">
                </div>
            </div>
            <div class="col-xs-5 coords">
                <div class="input-group">
                    <label class="input-group-addon"><?php echo  Yii::t('blocks/googlemap', 'Longitude');?></label>
                    <input type="text" class="form-control" id="googlemap_longitude">
                </div>
            </div>
            <span class="coords eHint"><?php echo  Yii::t('blocks/googlemap', 'Coordinates takes precedence over address');?></span>

        </div>
        <hr/>
        <div class="hidden" id="gmap_result_container"></div>

    </div>
    <div id="googlemap_apikey_error" class="alert alert-danger hidden"><?php echo  Yii::t('blocks/googlemap', 'To use this block you must set the Googlemap Api key in General Settings');?></div>
    <div class="row eRow saveButtonsRow">
        <span class="btn btn-danger toTrash"><i class="fa fa-trash"></i></span>
        <span class="btn btn-success pull-right proBox-save"><i class="fa fa-2x fa-check"></i></span>
    </div>
</div>