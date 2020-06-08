<?php

namespace Maestriam\Hiker\Entities;

class Hiker
{
    /**
     * Retorna a entidade para manipulação das funções
     * de menu
     *
     * @param string $name
     * @return void
     */
    public function menu(string $name)
    {
        return new Menu($name);
    }    
}
