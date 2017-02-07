<?php use common\components\Html;
use yii\helpers\src;

?>
<div id="proBox-contactdata">
    <div class="proBoxTitle">
        <span id="contactdataTitle" class=""><?php echo Yii::t('blocks/contactdata', 'Contact data');?></span>
    </div>
    <div class="eContainer">
        <div class="row eRow">
            <div class="col-sm-12 contactdata_instructions">
                <p><?php echo Yii::t('blocks/contactdata', 'Keep enabled the fields you want to show.');?></p>
            </div>
        </div>
        <div class="row eRow">
            <div class="col-sm-4 col-sm-offset-2">
                <ul class="list-group">
                    <li class="list-group-item">
                        <?php echo Yii::t('blocks/contactdata', 'Show title');?>
                        <div class="onoffswitch pull-right">
                            <input type="checkbox" id="contactdata_showtitle" class="onoffswitch-checkbox" name="contactdata_showtitle" value="1" checked="checked">
                            <label class="onoffswitch-label" for="contactdata_showtitle"></label>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <?php echo Yii::t('blocks/contactdata', 'Web name');?>
                        <div class="onoffswitch pull-right">
                            <input type="checkbox" id="contactdata_webname" class="onoffswitch-checkbox" name="contactdata_webname" value="1" checked="checked">
                            <label class="onoffswitch-label" for="contactdata_webname"></label>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <?php echo Yii::t('blocks/contactdata', 'Address');?>
                        <div class="onoffswitch  pull-right">
                            <input type="checkbox" id="contactdata_address" class="onoffswitch-checkbox" name="contactdata_address" value="1" checked="checked">
                            <label class="onoffswitch-label" for="contactdata_address"></label>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <?php echo Yii::t('blocks/contactdata', 'Opening hours');?>
                        <div class="onoffswitch  pull-right">
                            <input type="checkbox" id="contactdata_openinghours" class="onoffswitch-checkbox" name="contactdata_openinghours" value="1" checked="checked">
                            <label class="onoffswitch-label" for="contactdata_openinghours"></label>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <?php echo Yii::t('blocks/contactdata', 'Telephone');?>
                        <div class="onoffswitch  pull-right">
                            <input type="checkbox" id="contactdata_telephone" class="onoffswitch-checkbox" name="contactdata_telephone" value="1" checked="checked">
                            <label class="onoffswitch-label" for="contactdata_telephone"></label>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <?php echo Yii::t('blocks/contactdata', 'Mobile phone');?>
                        <div class="onoffswitch  pull-right">
                            <input type="checkbox" id="contactdata_mobile" class="onoffswitch-checkbox" name="contactdata_mobile" value="1" checked="checked">
                            <label class="onoffswitch-label" for="contactdata_mobile"></label>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <?php echo Yii::t('blocks/contactdata', 'Email');?>
                        <div class="onoffswitch  pull-right">
                            <input type="checkbox" id="contactdata_email" class="onoffswitch-checkbox" name="contactdata_email" value="1" checked="checked">
                            <label class="onoffswitch-label" for="contactdata_email"></label>
                        </div>
                    </li>
                </ul>

            </div>
            <div class="col-sm-4">
                <ul class="list-group">
                    <li class="list-group-item">
                        <?php echo Yii::t('blocks/contactdata', 'Facebook');?>
                        <div class="onoffswitch  pull-right">
                            <input type="checkbox" id="contactdata_facebook" class="onoffswitch-checkbox" name="contactdata_facebook" value="1" checked="checked">
                            <label class="onoffswitch-label" for="contactdata_facebook"></label>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <?php echo Yii::t('blocks/contactdata', 'Twitter');?>
                        <div class="onoffswitch  pull-right">
                            <input type="checkbox" id="contactdata_twitter" class="onoffswitch-checkbox" name="contactdata_twitter" value="1" checked="checked">
                            <label class="onoffswitch-label" for="contactdata_twitter"></label>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <?php echo Yii::t('blocks/contactdata', 'Instagram');?>
                        <div class="onoffswitch  pull-right">
                            <input type="checkbox" id="contactdata_instagram" class="onoffswitch-checkbox" name="contactdata_instagram" value="1" checked="checked">
                            <label class="onoffswitch-label" for="contactdata_instagram"></label>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <?php echo Yii::t('blocks/contactdata', 'Pinterest');?>
                        <div class="onoffswitch  pull-right">
                            <input type="checkbox" id="contactdata_pinterest" class="onoffswitch-checkbox" name="contactdata_pinterest" value="1" checked="checked">
                            <label class="onoffswitch-label" for="contactdata_pinterest"></label>
                        </div>
                    </li>
                </ul>



            </div>
        </div>
    </div>
    <div class="row eRow saveButtonsRow">
        <span class="btn btn-danger toTrash"><i class="fa fa-trash"></i></span>
        <span class="btn btn-success pull-right proBox-save"><i class="fa fa-2x fa-check"></i></span>
    </div>
</div>
