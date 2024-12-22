<?php

namespace app\models\exceptions;

use yii\web\HttpException;

class NoSuchGameTypeException extends HttpException
{
    public function __construct($code = 0, $previous = null)
    {
        parent::__construct(501, 'The game type has not been implemented yet.', $code, $previous);
    }
}
