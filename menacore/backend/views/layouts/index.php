<?php
use backend\assets\AppAsset;
use backend\assets\ContentAsset;
//use yii\helpers\Html;
use common\components\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use common\models\Configuration;

$this->title='MenaPRO '.Configuration::getValue('_WEB_NAME_');
AppAsset::register($this);
$this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?php echo  Yii::$app->language ?>">

<head>

    <meta charset="<?php echo  Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="<?php echo Url::base() ?>/../img/favicon.ico" type="image/x-icon" />
    <?php if($this->context->gmap_api_key!=""){?>
        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=places&key=<?php echo $this->context->gmap_api_key?>"></script>
    <?php } ?>
     <?php echo  Html::csrfMetaTags() ?>
    <title> <?php echo   Html::encode($this->title) ?></title>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!--[if lt IE 11]>
         <link rel="stylesheet" type="text/css" href="<?php echo Yii::getAlias('@web')?>/css/browsererror.css" />
    <![endif]-->
    <?php $this->head() ?>
</head>

<body>
<?php $this->beginBody() ?>

<?php
include("_topbar.php");


?>
<div class="eSpaceBar"></div>
<div id="wrapper">

    <!-- Sidebar -->
    <?php
    include("_pagesBar.php");
    ?>
    <!-- /#sidebar-wrapper -->
    <!-- News panel -->

<!--    --><?php //echo $this->render('_newsPanel')?>
    <?php include("_newsPanel.php"); ?>

    <!-- /News panel -->
    <!-- Page Content -->
    <div id="page-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="container-fluid">

                         <?php echo  Alert::widget() ?>
                         <?php echo  $content ?>

                    </div>


<!--                    <a class="btn btn-default" href="#" onclick="$('.tb_published,#users').toggleClass('mn_warning').attr('tooltip','Cannot catch moon');return false">Simular errores</a>-->
                </div>
            </div>
        </div>

    </div>
    <!-- /#page-content-wrapper -->

</div>

<div id="browser_error" class="alert alert-danger"><?php echo Yii::t('app','Su versión del navegador es demasiado antigua para trabajar con MenaPRO.');?></div>
<div id="device_error" class="alert alert-danger"><?php echo Yii::t('app','Su versión de MenaPRO no le permite editar desde éste dispositivo.');?></div>


<!-- /#wrapper -->
<?php

 $this->endBody()
?>
</body>

</html>
<?php $this->endPage() ?>