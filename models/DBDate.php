<?php

namespace app\models;

use DateTime;
use Yii;

class DBDate
{
    const string DB_DATE_FORMAT = 'Y-m-d H:i:s';

    static function getCurrentDate()
    {
//        return date(self::DB_DATE_FORMAT);
        return Yii::$app->getDb()->createCommand('SELECT CURRENT_TIMESTAMP();')->queryOne()['CURRENT_TIMESTAMP()'];
    }

    static function validateDate($date)
    {
        $d = DateTime::createFromFormat(self::DB_DATE_FORMAT, $date);
        return $d && $d->format(self::DB_DATE_FORMAT) === $date;
    }
}
