<?php

return [
    'language'=>'es-ES',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',

    'bootstrap'=>[
        'yii\i18n\PhpMessageSource',
        'yii\helpers\Html'
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [

        'i18n' => [

            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                    'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php',
                    ],
                ],
                'blocks*' => [
                    'class' => 'common\components\PhpBlockMessageSource',
                    'basePath' => '@menaBase/',
                    'sourceLanguage' => 'en-US',
                    'fileMap' => [


                    ],
                ]
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@menaBase/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

    ],

];
