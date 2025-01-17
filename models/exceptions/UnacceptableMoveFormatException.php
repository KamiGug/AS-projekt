<?php

namespace app\models\exceptions;

use yii\web\HttpException;

class UnacceptableMoveFormatException extends HttpException
{
    public function __construct($code = 0, $previous = null)
    {
        parent::__construct(400, 'Bad move format.', $code, $previous);
    }
}
