<?php

namespace app\models\exceptions;

use yii\web\HttpException;

class BadBoardStateException extends HttpException
{
    public function __construct($code = 0, $previous = null)
    {
        parent::__construct(500, 'Board state has been corrupted!', $code, $previous);
    }
}
