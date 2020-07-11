<?php

namespace Maestriam\Hiker\Entities;

use Maestriam\Hiker\Entities\Menu;
use Maestriam\Hiker\Entities\Breadcrumb;
use Maestriam\Hiker\Traits\Foundation\ManagesCache;

class Hiker
{
    use ManagesCache;

    /**
     * Inicializando
     */
    public function __construct()
    {
        $this->cache()->purge();
    }

    /**
     * Retorna a entidade para manipulação das funções
     * de menu
     *
     * @param string $name
     * @return Menu
     */
    public function menu(string $name) : Menu
    {
        return new Menu($name);
    }    

    /**
     * Retorna a entidade para manipulação das funções
     * de menu
     *
     * @param string $name
     * @return Menu
     */
    public function breadcrumb(string $name) : Breadcrumb
    {
        return new Breadcrumb($name);
    }  
}
