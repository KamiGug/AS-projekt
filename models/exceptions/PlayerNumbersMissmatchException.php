<?php

namespace app\models\exceptions;

use yii\web\HttpException;

class PlayerNumbersMissmatchException extends HttpException
{
    public function __construct($code = 0, $previous = null)
    {
        parent::__construct(500, 'An error has occurred regarding player numbers!', $code, $previous);
    }
}
