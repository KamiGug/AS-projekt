<?php

namespace app\models\exceptions;

use yii\web\HttpException;

class UnacceptableHistoryFormatException extends HttpException
{
    public function __construct($code = 0, $previous = null)
    {
        parent::__construct(500, 'Bad history format.', $code, $previous);
    }
}
