<?php
/**
 * Created by PhpStorm.
 * User: silvia
 * Date: 31/10/2016
 * Time: 15:07
 */

use yii\helpers\Url;

if (!isset(Yii::$app->session['accept_cookies']) || Yii::$app->session['accept_cookies'] == false):?>
    <div id="cookies_notification">
    <span id="close_cookies_notification"><i class="fa fa-times pull-right" ></i></span>
    <?php echo Yii::t('app',"We use cookies to give you the best experience. If you do nothing, we´ll assume that's ok.") ?>
        <br>
        <a id="accept-cookies" href="<?= Url::to(['/site/accept-cookies'])?>" class="btn btn-mena pull-right" rel="nofollow">Aceptar cookies</a>

    </div>
<?php endif;
