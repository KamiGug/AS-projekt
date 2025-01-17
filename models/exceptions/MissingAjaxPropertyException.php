<?php

namespace app\models\exceptions;

use yii\web\HttpException;

class MissingAjaxPropertyException extends HttpException
{
    public function __construct($property, $code = 0, $previous = null)
    {
        parent::__construct(406, 'Request is missing the \''
            . $property
            . '\' property in the request body', $code, $previous);
    }
}
