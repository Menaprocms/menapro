<?php
$params1 = require(__DIR__ . '/params.php');
$params2 = require(__DIR__ . '/../../common/config/params.php');
$params=array_merge($params1,$params2);

$config = [
    'language' => 'en_EN',
    'id' => 'MenaPRO',
    'basePath' => dirname(__DIR__),

    'controllerNamespace' => 'frontend\controllers',
    'bootstrap'=>['assetsAutoCompress'],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'assetManager' => [
            'basePath' => '@webroot/public_assets',
            'baseUrl' => '@web/public_assets',
            'bundles' => [
                'yii\web\JqueryAsset' => false,
            ],
        ],
        'assetsAutoCompress' =>
            [
                'class' => 'frontend\components\CustomAssetsAutoCompressComponent',//'skeeks\yii2\assetsAuto\AssetsAutoCompressComponent',
                'htmlCompress'=>true,
                'htmlCompressOptions'=>[
                    'extra'=>true
                ],
            ],
        'Controller' => [
            'class' => 'frontend\components\Controller'
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'suffix' => false,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'rules' => [
                'site/accept-cookies' => 'site/accept-cookies',
                'block/block.html' => 'block/block',
                'site/<action:\w+>' => 'site/<action>',
                [
                    'class' => 'frontend\components\ContentUrlRule'
                ],
            ],
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'request'=>[
            'csrfParam'=>"menacsrf"
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {

    if(is_array($config['bootstrap']))
    {
        $config['bootstrap'][] = 'debug';
    }else
    {
        $config['bootstrap'] = ['debug'];
    }

    $config['modules'] = [
        'debug' => [
            'class' => 'yii\debug\Module',
            'allowedIPs' => ['127.0.0.1', '::1']
        ],
    ];
}


return $config;
