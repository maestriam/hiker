<?php

namespace Maestriam\Hiker\Entities;

use Maestriam\Hiker\Entities\Menu;
use Maestriam\Hiker\Entities\Breadcrumb;
use Maestriam\Hiker\Foundation\CacheHandler;

class Hiker
{
    public function __construct()
    {
        $this->purge();
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

    /**
     * Apaga TODAS as chaves salvas pelo Hiker
     *
     * @return Hiker
     */
    public function purge() : Hiker
    {
        $cache = new CacheHandler();
        $cache->purge();

        return $this;
    }
}
