<?php

use yii\helpers\ArrayHelper;

$db = [
    'class' => 'yii\db\Connection',
    'dsn' =>'',
    'username' => '',
    'password' => '',
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    ...(defined('YII_ENV') && YII_ENV === 'PROD'
        ? [
            'enableSchemaCache' => true,
            'schemaCacheDuration' => 60,
            'schemaCache' => 'cache',
        ]
        : []
    )
];

if (defined('YII_ENV')) {
    switch (strtoupper(YII_ENV)) {
        case 'PROD': 
            $db = ArrayHelper::merge($db, require(__DIR__ . '/db/prod.php'));
            break;
        case 'DEV':
            $db = ArrayHelper::merge($db, require(__DIR__ . '/db/dev.php'));
            break;
        case 'STAGE':
            $db = ArrayHelper::merge($db, require(__DIR__ . '/db/stage.php'));
            break;
    }
}

return $db;
