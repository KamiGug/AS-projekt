<?php

namespace app\models\exceptions;

use yii\web\HttpException;

class BadFilterFormatException extends HttpException
{
    public function __construct($code = 0, $previous = null)
    {
        parent::__construct(400, 'Bad Filter Format!', $code, $previous);
    }
}
