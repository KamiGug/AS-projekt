<?php

namespace app\models\exceptions;

use yii\web\HttpException;

class NoSuchRoomException extends HttpException
{
    public function __construct($roomId, $code = 0, $previous = null)
    {
        parent::__construct(404, 'Room with id ' . $roomId . 'does not exit.', $code, $previous);
    }
}
