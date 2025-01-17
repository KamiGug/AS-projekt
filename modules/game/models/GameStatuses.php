<?php

namespace app\modules\game\models;

use app\modules\game\models\base\BaseGameType;
use app\modules\game\models\ludo\LudoGameType;

class GameStatuses
{
    const STATUS_WAITING = 'waiting';
    const STATUS_PLAYING = 'playing';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_FINISHED = 'finished';

    const array STATUSES = [
        self::STATUS_WAITING,
        self::STATUS_PLAYING,
        self::STATUS_CANCELLED,
        self::STATUS_FINISHED,
    ];
}
