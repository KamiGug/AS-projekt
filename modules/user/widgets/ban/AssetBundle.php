<?php

namespace app\modules\user\widgets\ban;

use app\models\AssetBundle as Base;

class AssetBundle extends Base
{
    public $basePath = __DIR__ ;
    public $dynamicJs = [
        'dyn-js/index.js.php' => [],
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
