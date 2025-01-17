<?php

namespace app\models\exceptions;

use yii\web\HttpException;

class NotActivePlayerException extends HttpException
{
    public function __construct($code = 0, $previous = null)
    {
        parent::__construct(417, 'You are not the active player!', $code, $previous);
    }
}
