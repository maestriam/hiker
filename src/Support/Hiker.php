<?php

namespace Maestriam\Hiker\Support;

use Maestriam\Hiker\Entities\Menu;
use Maestriam\Hiker\Entities\Hiker as HikerModel;

class Hiker
{
    public static function menu(string $menu) : Menu
    {
        $hiker = new HikerModel();

        return $hiker->menu($menu);
    }
}