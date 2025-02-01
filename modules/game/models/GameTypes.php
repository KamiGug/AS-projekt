<?php

namespace app\modules\game\models;

use app\modules\game\models\base\BaseGameType;
use app\modules\game\models\ludo\LudoGameType;
use Yii;

class GameTypes
{
    const TYPE_BASE = 'base';
    const TYPE_LUDO = 'ludo';

    const array GAME_TYPE_MAP = [
        GameTypes::TYPE_LUDO => LudoGameType::class,
    ];

    public static function getGameTypeNames () : array
    {
        return [
            GameTypes::TYPE_LUDO => Yii::t('app', 'Ludo'),
        ];
    }
}
