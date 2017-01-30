<?php

defined('YII_DEBUG') or define('YII_DEBUG', false);
defined('YII_ENV') or define('YII_ENV', 'prod');

defined('MENAPRO675') or define('MENAPRO675',true);
require(__DIR__ . '/../config/core_routes.php');
require(__DIR__ . '/../config/core_hashes.php');
if(defined('CORE_ROUTE')) {
    require(__DIR__ . '/../' . CORE_ROUTE . '/vendor/autoload.php');
    require(__DIR__ . '/../' . CORE_ROUTE . '/vendor/yiisoft/yii2/Yii.php');
    require(__DIR__ . '/../' . CORE_ROUTE . '/common/config/bootstrap.php');

    if (is_dir(__DIR__ . '/../install') && file_exists(__DIR__ . '/../config/installed.txt')) {
        die('<div class="alert alert-warning" style="border:1px solid #E6AD48;max-width: 350px;padding: 5px;border-radius: 7px;background-color: #F3E5B5;text-align: center;">' . Yii::t('app', 'Para acceder al gestor debe borrar la carpeta install') . '</div>');
    } else {
        $config = yii\helpers\ArrayHelper::merge(
            require(__DIR__ . '/../' . CORE_ROUTE . '/common/config/main.php'),
            require(__DIR__ . '/../' . CORE_ROUTE . '/backend/config/main.php'),
            require(__DIR__ . '/../config/config_data.php')
        );
        $application = new yii\web\Application($config);
        $application->run();
    }
}else{
    die('NO EXISTE COREROUTE');
}


