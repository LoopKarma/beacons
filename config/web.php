<?php

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

$config = [
    'id' => 'main',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'ru-RU',
    'timeZone' => 'Europe/Moscow',
    'aliases' => [
        '@admin' => realpath(dirname(__FILE__).'/../')
    ],
    'components' => [
        'urlManager' => [
            'showScriptName' => false,
            'enablePrettyUrl' => true,
        ],
        'request' => [
            'cookieValidationKey' => 'qbyl9TQc_1Cqn9rdqntXefal0vpL-aNc',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'loginUrl' => ['user/login'],
            'authTimeout' => '86400',
            'enableAutoLogin' => true,
            'on afterLogin' => function ($event) {
                Yii::$app->user->identity->afterLogin($event);
            },
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'pass' => [
            'class' => 'app\components\Pass',
            'passFilePath' => '@admin/files/pass/',
            'wwdrCertPath' => '@admin/files/cert/WWDR.pem',
            'teamIdentifier' => '8V4MJ9GE5G',
            'certificatePassword' => 'getcoupon123'
        ],
        'db' => $db,
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['192.168.56.1']
    ];
}

return $config;
