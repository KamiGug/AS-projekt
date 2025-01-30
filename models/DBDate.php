<?php

namespace app\models;

use DateTime;

class DBDate
{
    const string DB_DATE_FORMAT = 'Y-m-d H:i:s';

    static function getCurrentDate() : string
    {
        return date(self::DB_DATE_FORMAT);
    }

    static function validateDate($date)
    {
        $d = DateTime::createFromFormat(self::DB_DATE_FORMAT, $date);
        return $d && $d->format(self::DB_DATE_FORMAT) === $date;
    }
}
