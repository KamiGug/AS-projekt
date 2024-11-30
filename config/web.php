<?php

$modules = [
    //all web-based necessities, like login form
    'main' => ['class' => 'app\modules\main\Module'],

    //RESTful API allowing for ajax calls to specific games
    'game' => ['class' => 'app\modules\game\Module'],

    //all chat specific code - either websockets or views + RESTful API
    'chat' => ['class' => 'app\modules\chat\Module'],

    //profile, edit profile, view game history
    'user' => ['class' => 'app\models\user\Module'],
];

$pathingRules = [
    '/' => 'main/default/index',
    '/login' => 'main/authentication/login',
    '/logout' => 'main/authentication/logout',
    '/signup' => 'main/authentication/signup',
];

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'modules' => $modules,
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '2lE28pun0hQpCrp12lmHNK9hPP2Xulo2',
        ],
//        'cache' => [
//            'class' => 'yii\caching\FileCache',
//        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => $pathingRules,
        ],
        'user' => [
            'identityClass' => 'app\models\user\User',
            'enableAutoLogin' => true,
            'loginUrl' => '/login'
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
//        'mailer' => [
//            'class' => \yii\symfonymailer\Mailer::class,
//            'viewPath' => '@app/mail',
//            // send all mails to a file by default.
//            'useFileTransport' => true,
//        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
