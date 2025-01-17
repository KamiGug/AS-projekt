<?php

namespace app\models\exceptions;

use yii\web\HttpException;

class IllegalMoveException extends HttpException
{
    public function __construct($code = 0, $previous = null)
    {
        parent::__construct(417, 'Provided move is not legal!', $code, $previous);
    }
}
