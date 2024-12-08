<?php

namespace app\modules\user\models\Authentication;

use Yii;

class Role
{
    const ROLE_PLAYER = 'player';
    const ROLE_TEMPORARY_PLAYER = 'tmp_player';
    const ROLE_MODERATOR = 'moderator';
    const ROLE_ADMINISTRATOR = 'admin';

    public static function getRoles() : array {
        return [
            self::ROLE_PLAYER => Yii::t('app', 'Player'),
            self::ROLE_TEMPORARY_PLAYER => Yii::t('app', 'Guest'),
            self::ROLE_MODERATOR => Yii::t('app', 'Moderator'),
            self::ROLE_ADMINISTRATOR => Yii::t('app', 'Admin'),
        ];
    }
}
