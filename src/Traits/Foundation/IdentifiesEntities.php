<?php

namespace Maestriam\Hiker\Traits\Foundation;

use Maestriam\Hiker\Entities\Route;
use Maestriam\Hiker\Entities\Menu;
use Maestriam\Hiker\Entities\Breadcrumb;

/**
 * Funções compartilhadas para verificação 
 */
trait IdentifiesEntities
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

    /**
     * Verifica se o objeto em questão é 
     * uma instância de rota
     *
     * @return boolean
     */
    public function isBreadcrumb() : bool
    {
        return ($this instanceof Breadcrumb);
    }
}
