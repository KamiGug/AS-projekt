<?php

namespace app\models\exceptions;

use yii\web\HttpException;

class NotInTheChosenRoomException extends HttpException
{
    public function __construct($code = 0, $previous = null)
    {
        parent::__construct(400, 'You are not in that room!', $code, $previous);
    }
}
