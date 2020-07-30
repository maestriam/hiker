<?php

namespace Maestriam\Hiker\Exceptions;

use Exception;

class InvalidParamRouteException extends Exception
{
    public function __construct(string $key, string $route)
    {
        
    }
}
