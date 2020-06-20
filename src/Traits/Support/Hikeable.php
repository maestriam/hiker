<?php

namespace Maestriam\Hiker\Traits\Support;

use Maestriam\Hiker\Entities\Hiker;

/**
 * 
 */
trait Hikeable
{
    private static $hikerInstance;

    public function hiker()
    {
        if (isset($hikerInstance)) {
            return self::$hikerInstance;
        }

        self::$hikerInstance = new Hiker();
        return self::$hikerInstance;
    }
}