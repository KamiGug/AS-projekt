<?php

namespace app\modules\game\widgets\newRoom;

use app\models\AssetBundle as Base;

class AssetBundle extends Base
{
    public $basePath = __DIR__ ;
    public $dynamicJs = [
        'dyn-js/roomForm.js.php' => [],
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\widgets\ActiveFormAsset'
    ];

    public function registerAssetFiles($view): void
    {
        parent::registerAssetFiles($view);
    }
}
