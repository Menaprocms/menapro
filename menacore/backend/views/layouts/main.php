<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
//use yii\helpers\Html;
use common\components\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang=" <?php echo  Yii::$app->language ?>">
<head>
    <meta charset=" <?php echo  Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="<?php echo Url::base() ?>/img/favicon.ico" type="image/x-icon" />
     <?php echo  Html::csrfMetaTags() ?>
    <title> <?php echo  Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'My Company',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => 'Home', 'url' => ['/site/index']],
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        $menuItems[] = [
            'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
            'url' => ['/site/logout'],
            'linkOptions' => ['data-method' => 'post']
        ];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
         <?php echo  Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
         <?php echo  Alert::widget() ?>
         <?php echo  $content ?>
<!--        <div id="browser_error" class="alert alert-danger">--><?php //echo Yii::t('app','Su versión del navegador es demasiado antigua para traajar con MenaPRO.');?><!--</div>-->
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company  <?php echo  date('Y') ?></p>

        <p class="pull-right"> <?php echo  Yii::powered() ?></p>
    </div>
</footer>
<?php
echo Html::tag('iframe','',['id'=>'snapshot_iframe','class'=>'hidden','src'=>'']);
 $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
