<?php

namespace app\models;

class DBDate
{
    const string DB_DATE_FORMAT = 'Y-m-d H:i:s';

    static function getCurrentDate() : string
    {
        return date(self::DB_DATE_FORMAT);
    }
}
