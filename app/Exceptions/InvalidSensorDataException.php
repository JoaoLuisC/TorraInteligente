<?php

namespace App\Exceptions;

use Exception;

class InvalidSensorDataException extends Exception
{
    protected $message = 'Dados do sensor inválidos';
    protected $code = 422;
}
