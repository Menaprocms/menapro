<?php

defined('MENAPRO675') or define('MENAPRO675',true);



require(__DIR__ . '/config/core_routes.php');
require(__DIR__ . '/config/core_hashes.php');
ini_set('display_errors','On');
if(defined('CORE_ROUTE')) {

    defined('YII_DEBUG') or define('YII_DEBUG', false);
    //Debug only
//    require(__DIR__ . '/'.CORE_ROUTE.'/common/config/debug.php');

    defined('YII_ENV') or define('YII_ENV', 'prod');
//    ini_set('display_errors','Off');

    require(__DIR__ . '/'.CORE_ROUTE.'/vendor/autoload.php');
    require(__DIR__ . '/'.CORE_ROUTE.'/vendor/yiisoft/yii2/Yii.php');
    require(__DIR__ . '/'.CORE_ROUTE.'/common/config/bootstrap.php');



     if ((is_dir(__DIR__ . '/install') && !file_exists(__DIR__ . '/config/installed.txt')) || (isset($_REQUEST['action']) && $_REQUEST['action']=='removeinstall')) {

         include(__DIR__ . '/install/controllers/InstallController.php');
         $contr = new InstallController();
         $action=isset($_GET['action'])?$_GET['action']:'index';

         $contr->runAction($action);

    } else {
        if (is_dir(__DIR__ . '/install') && file_exists(__DIR__ . '/config/installed.txt')) {
            die('<div class="alert alert-warning" style="border:1px solid #E6AD48;max-width: 350px;padding: 5px;border-radius: 7px;background-color: #F3E5B5;text-align: center;">' . Yii::t('app', 'Para comenzar debe borrar la carpeta install') . '</div>');
        } else {
            $config = yii\helpers\ArrayHelper::merge(
                require(__DIR__ . '/'.CORE_ROUTE.'/common/config/main.php'),
                require(__DIR__ . '/'.CORE_ROUTE.'/frontend/config/main.php'),
                require(__DIR__ . '/config/config_data.php')
            );
            $application = new yii\web\Application($config);
            $application->run();
        }

    }
}else{
    die("Unable to find core files.");
}