<?php

namespace App\Exceptions;

use Exception;

class InsufficientPointsException extends Exception
{
    protected $message = 'امتیاز شما کافی نیست.';
}