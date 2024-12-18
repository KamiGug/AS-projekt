<?php

namespace app\modules\game\models;

class RoomList
{
    const TYPE_LIST = 'list';
    const TYPE_TILESET = 'tile';
    static $allowedTypes = [self::TYPE_LIST,  self::TYPE_TILESET];
    public static function getType($initialType)
    {
        return in_array($initialType, self::$allowedTypes) ? $initialType : self::TYPE_LIST;
    }

}
