<?php

namespace app\modules\user\models\Authentication;

use Yii;

class BanType
{
    const BAN_TYPE_ALL = 'all';
    const BAN_TYPE_CHAT = 'chat';

    public static function getBanTypes() : array {
        return [
            self::BAN_TYPE_ALL => Yii::t('app', 'All'),
            self::BAN_TYPE_CHAT => Yii::t('app', 'Chat'),
        ];
    }
}
