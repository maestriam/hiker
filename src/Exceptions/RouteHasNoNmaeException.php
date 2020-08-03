<?php

namespace Maestriam\Hiker\Exceptions;

use Exception;

class RouteHasNoNameException extends Exception
{
    protected $message;

    public function __construct(string $uri)
    {
        $this->message = "The {$uri} has no name. Set a name for laravel route.";   
    }
}
