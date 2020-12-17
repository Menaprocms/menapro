<?php
use common\widgets\Captcha;

?>
<div class="eContactformBlock">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group name">
                <label for="name"><?php echo Yii::t('blocks/contactform', 'Your name'); ?> :</label>
                <input id="name" class="form-control" name="name" type="text" value="" size="30"/>
            </div>
            <div class="form-group email">
                <label for="email"><?php echo Yii::t('blocks/contactform', 'Your email'); ?> :</label>
                <input id="email" class="form-control" name="email" type="text" value="" size="30"/>
            </div>


            <div class="form-group message">
                <label for="message"><?php echo Yii::t('blocks/contactform', 'Your message'); ?> :</label>
                <textarea id="message" class="form-control" name="message" rows="7" cols="30"></textarea>
            </div>
            <div class="form-group">
                <label for=""><?php echo Yii::t('blocks/contactform',"Security code") ?></label>
                <?php echo Captcha::widget([
                    'name' => 'captcha',
                ]); ?>
            </div>
            <a id="send_button"
               class="btn btn-default pull-right"><?php echo Yii::t('blocks/contactform', 'Send'); ?> </a>
        </div>
    </div>


</div>
<div class="alert alert-success hidden send_alert"
     id="send_ok"><?php echo Yii::t('blocks/contactform', 'Your email has been sent successfully'); ?></div>
<div class="alert alert-danger hidden send_alert"
     id="send_fail"><?php echo Yii::t('blocks/contactform', 'An error ocurred sending your email'); ?></div>
<div class="alert alert-danger hidden send_alert"
     id="captcha_error"><?php echo Yii::t('blocks/contactform', 'Incorrect captcha'); ?></div>
<div class="alert alert-warning hidden send_alert"
     id="send_invalid"><?php echo Yii::t('blocks/contactform', 'You must fill all fields'); ?></div>
