<?php

/* @var $this \yii\web\View */
/* @var $content string */
use backend\assets\loginAsset;
use backend\assets\AppAsset;
//use yii\helpers\Html;
use common\components\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

loginAsset::register($this);
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang=" <?php echo  Yii::$app->language ?>">
<head>
    <meta charset="UTF-8">

    <meta charset=" <?php echo  Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <?php echo  Html::csrfMetaTags() ?>
    <title><?php echo Yii::t('app','MenaPro Login')?></title>
    <?php $this->head() ?>
</head>

<body>
<?php $this->beginBody() ?>

<div class="wrapper">
    <div class="container">

         <?php echo  $content ?>


    </div>

    <ul class="bg-bubbles">
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
    </ul>
</div>




 <?php echo  $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
