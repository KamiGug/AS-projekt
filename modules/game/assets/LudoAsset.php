<?php

namespace app\modules\game\assets;

use yii\web\AssetBundle;

class LudoAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . '/../js/ludo';
    public $css = [
//        '@web/web-assets/game/widgets/game/css/game.css',
//        '@web/web-assets/game/widgets/game/css/index.css',
    ];
    public $js = [
        'game.js',

        'prefabs/Board.js',
        'prefabs/BoardSpace.js',
        'prefabs/Dice.js',
        'prefabs/Piece.js',


        'scenes/BaseScene.js',
//        'scenes/LoadingScene.js',
        'scenes/MainScene.js',

        'menu/Sidebar.js',
        'menu/SeatWrapper.js',
        'menu/Seat.js',
        'menu/DiceTray.js',
        'menu/Chat.js',
        'menu/LeaveButton.js',

    ];
    public $depends = [
        'app\modules\game\assets\PhaserAsset',
        'app\modules\game\widgets\game\AssetBundle',
    ];
}
