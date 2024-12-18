<?php

namespace app\modules\game\widgets\game;

use yii\web\AssetBundle as Base;

class AssetBundle extends Base
{
    public $basePath = __DIR__;
    public $baseUrl = '@web/web-assets/game/widgets/game/';
    public $css = [
        'css/loader.css',
    ];
    public $js = [
        'js/index.js',
        'js/buildList.js',
        'js/buildRoom.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
