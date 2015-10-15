<?php

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

$config = [
    'id' => 'api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'ru-RU',
    'timeZone' => 'Europe/Moscow',
    'aliases' => [
        '@admin' => realpath(dirname(__FILE__).'/../')
    ],
    'modules' => [
        'v1' => [
            'class' => 'app\api\modules\v1\Module',
        ],
    ],
    'components' => [
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                'v1/coup' => 'v1/default/index',
                'v1/check' => 'v1/default/check',
            ],
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'format' => yii\web\Response::FORMAT_RAW,
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => false,
            'enableSession' => false,
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    'logVars' => ['_POST', '_GET'],
                    'logFile' => '@app/runtime/logs/api.log'
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

return $config;
