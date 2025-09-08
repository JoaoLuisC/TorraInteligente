<?php

namespace App\Exceptions;

use Exception;

class DeviceNotFoundException extends Exception
{
    protected $message = 'Device não encontrado';
    protected $code = 404;
}
