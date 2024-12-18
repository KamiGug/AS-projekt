<?php

$modules = [
    //all web-based necessities, like login form
    'main' => ['class' => 'app\modules\main\Module'],

    //RESTful API allowing for ajax calls to specific games
    'game' => ['class' => 'app\modules\game\Module'],

    //all chat specific code - either websockets or views + RESTful API
    'chat' => ['class' => 'app\modules\chat\Module'],

    //profile, edit profile, view game history
    'user' => ['class' => 'app\modules\user\Module'],
];

$pathingRules = [
//    '/game/room' => 'game/room/rejoin',
    '/' => 'main/default/index',
    '/login' => 'user/authentication/login',
    '/logout' => 'user/authentication/logout',
    '/signup' => 'user/authentication/signup',
    '/profile/<id:\d+>' => 'user/management/profile',
    '/profile/edit/<id:\d+>' => 'user/management/editProfile',
    '/chat/<action:[\w-]+>' => 'chat/default/<action>',

    '/game/<controller:\w+>/<action:\w+>' => 'game/<controller>/<action>',


    //fallback simple routing
    '/<action:[\w-]+>' => 'main/default/<action>',
    '/<controller:[\w-]+>/<action:[\w-]+>' => 'main/<controller>/<action>',
    '/<module:\w+>/<controller:w+>/<action:w+>' => '<module>/<controller>/<action>',
];

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'as-projekt',
    'name' => 'Kurnik-clone',
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
            'normalizer' => [
                'class' => 'yii\web\UrlNormalizer',
//                'collapseSlashes' => true, // Collapse consecutive slashes into one
//                'normalizeTrailingSlash' => true, // Remove/add trailing slash based on rules
            ],
        ],
        'user' => [
            'identityClass' => 'app\modules\user\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => '/login',
            'accessChecker' => [
                'class' => 'app\modules\user\models\Authentication\AccessChecker'
            ]
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
