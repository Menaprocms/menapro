<?php
/**
 * Created by PhpStorm.
 * User: silvia
 * Date: 31/10/2016
 * Time: 15:07
 */

if(!isset(Yii::$app->session['accept_cookies']) || Yii::$app->session['accept_cookies']==false){
    Yii::$app->session['accept_cookies']=true;
    ?>
<div id="cookies_notification">
    <span id="close_cookies_notification"><i class="fa fa-times pull-right" ></i></span>
    <?php echo Yii::t('app',"We use cookies to give you the best experience. If you do nothing, we´ll assume that's ok.") ?>
</div>
<?php } ?>