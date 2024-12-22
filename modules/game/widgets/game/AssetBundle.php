<?php

namespace app\modules\game\widgets\game;

use app\models\AssetBundle as Base;

class AssetBundle extends Base
{
    public $basePath = __DIR__;
    public $baseUrl = '@web/web-assets/game/widgets/game/';
    public $css = [
        'css/index.css',
        'css/list.css',
        'css/game.css'
    ];
    public $js = [
        'js/index.js',
        'js/buildList.js',
        'js/buildGameRoom.js',
    ];

    public $dynamicJs = [
        'dyn-js/gameTypes.js.php' => [],
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
