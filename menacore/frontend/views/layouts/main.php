<?php

/* @var $this \yii\web\View */
/* @var $content string */

use common\components\Html;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;
use frontend\assets\AppAsset;
use common\widgets\Responsivenav;
use common\widgets\languageSwitcher;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?php echo Yii::$app->language ?>">
<head>
    <meta charset="<?php echo  Yii::$app->charset ?>">
    <link rel="shortcut icon" href="<?php echo Url::base() ?>/img/favicon.ico" type="image/x-icon" />
    <?php
          $baseUrl = Yii::$app->homeUrl;
    ?>
     <?php echo  Html::csrfMetaTags() ?>
    <title> <?php echo  Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="wrap">

    <div class="container">
        <?php echo languageSwitcher::Widget();?>
        <?php echo Responsivenav::widget([
            'items' => $this->context->menu,
        ]) ?>

         <?php echo  $content ?>
    </div>
</div>
<?php

    if($this->context->config['_COOKIES_NOTIFICATION_']){
        echo  $this->renderDynamic('return $this->renderFile("@frontend/views/content/cookies_notice.php");');
    }

?>
<footer class="footer">

</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
