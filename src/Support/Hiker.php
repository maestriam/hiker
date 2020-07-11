<?php

namespace Maestriam\Hiker\Support;

use Maestriam\Hiker\Entities\Menu;
use Maestriam\Hiker\Entities\Hiker as HikerModel;
use Maestriam\Hiker\Entities\Breadcrumb;

abstract class Hiker
{
    private static $hiker = null;

    /**
     * Retorna a instância das regras
     *
     * @return void
     */
    public static function getInstance() : HikerModel
    {
        if (! isset(self::$hiker)) {
            self::$hiker = new HikerModel();
        }

        return self::$hiker;
    }

    /**
     * Undocumented function
     *
     * @param string $menu
     * @return Menu
     */
    public static function menu(string $menu) : Menu
    {
        $hiker = self::getInstance();
        
        return $hiker->menu($menu);
    }
    
    /**
     * Undocumented function
     *
     * @param string $menu
     * @return Breadcrumb
     */
    public static function breadcrumb(string $menu) : Breadcrumb
    {
        $hiker = self::getInstance();

        return $hiker->breadcrumb($menu);
    }
}