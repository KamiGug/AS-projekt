<?php

namespace app\models\exceptions;

use yii\web\HttpException;

class PlayerNumberIsTakenException extends HttpException
{
    public function __construct($code = 0, $previous = null)
    {
        parent::__construct(400, 'Chosen player number is already taken!', $code, $previous);
    }
}
