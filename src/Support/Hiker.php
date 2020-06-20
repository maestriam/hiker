<?php

namespace Maestriam\Hiker\Support;

use Maestriam\Hiker\Entities\Hiker as Model;

class Hiker
{
    public static function menu(string $menu)
    {
        $hiker = new Model();

        return $hiker->menu($menu);
    }
}