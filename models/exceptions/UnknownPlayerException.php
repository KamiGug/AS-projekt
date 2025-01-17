<?php

namespace app\models\exceptions;

use yii\web\HttpException;

class UnknownPlayerException extends HttpException
{
    public function __construct($code = 0, $previous = null)
    {
        parent::__construct(401, 'Unable to recognize the player! Are you logged in?', $code, $previous);
    }
}
