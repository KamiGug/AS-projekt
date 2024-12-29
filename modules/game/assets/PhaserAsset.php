<?php

namespace app\modules\game\assets;

use yii\web\AssetBundle;

class PhaserAsset extends AssetBundle
{
//    public $basePath = '@webroot';
//    public $baseUrl = '@web';
    public $sourcePath = '@npm/phaser/dist';
    public $css = [];
    public $js = [
        'phaser.min.js',
    ];
}
