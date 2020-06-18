<?php

namespace Maestriam\Hiker\Traits\Entities;

use Maestriam\Hiker\Entities\Route;
use Maestriam\Hiker\Entities\Menu;

/**
 * Funções compartilhadas para verificação 
 */
trait SelfKnowledge
{
    /**
     * Verifica se o objeto em questão é 
     * uma instância de menu
     *
     * @return boolean
     */
    public function isMenu() : bool
    {
        return ($this instanceof Menu);
    }

    /**
     * Verifica se o objeto em questão é 
     * uma instância de rota
     *
     * @return boolean
     */
    public function isRoute() : bool
    {
        return ($this instanceof Route);
    }
}
