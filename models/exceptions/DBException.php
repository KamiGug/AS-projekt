<?php

namespace app\models\exceptions;

use yii\web\HttpException;

class DBException extends HttpException
{
    public function __construct($code = 0, $previous = null)
    {
        parent::__construct(500, 'Temporary problem with database', $code, $previous);
    }
}
