<?php

namespace Maestriam\Hiker\Support;


use Illuminate\Support\Facades\Facade;

class Hiker extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'hiker';
    }
}