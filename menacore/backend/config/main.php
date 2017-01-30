<?php
$params1 = require(__DIR__ . '/params.php');
$params2 = require(__DIR__ . '/../../common/config/params.php');
$params=array_merge($params1,$params2);
return [
    'id' => 'app-backend',
    'layout'=>'index.php',
    'defaultRoute'=>'content',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'components' => [
        'supervisor'=>[
            'class'=>'backend\components\Supervisor'
        ],
        'cacheFrontend' => [
            'class' => 'yii\caching\FileCache',
            'cachePath' => Yii::getAlias('@frontend') . '/runtime/cache'
        ],
        'request'=>[
            'csrfParam'=>"menacsrf"
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => [
                'name' => '_menaPROuser'.COOKIE_HASH, // unique for backend
                'path' => '@backend' // correct path for backend app.
            ]
        ],
        // unique identity session parameter for backend
        'session' => [
            'name' => '_menaPRO'.SESSION_HASH,
            'savePath' => __DIR__ . '/../runtime/sessions',
        ],

        'urlManagerFrontend' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'suffix'=>'.html',
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                [
                    'class'=>'frontend\components\ContentUrlRule',
                ],

            ],
        ],
    ],
    'params' => $params,
];
