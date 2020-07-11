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
        if (! isset(self::$hikerInstance)) {
            self::$hikerInstance = new Hiker();
        }

        return self::$hikerInstance;
    }
}