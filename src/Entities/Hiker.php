<?php

namespace Maestriam\Hiker\Entities;

use Maestriam\Hiker\Entities\Menu;

class Hiker
{
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
}
