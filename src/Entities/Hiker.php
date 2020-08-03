<?php

namespace Maestriam\Hiker\Entities;

use Maestriam\Hiker\Entities\Menu;
use Maestriam\Hiker\Entities\Breadcrumb;
use Maestriam\Hiker\Traits\Foundation\ManagesCache;
use Maestriam\Hiker\Traits\Foundation\ManagesRoutes;
use Maestriam\Hiker\Exceptions\BreadcrumbNotFoundException;

class Hiker
{
    use ManagesCache, ManagesRoutes;

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
     * Retorna um breadcrumb de acordo com o nome.
     * Se nenhum nome for passado, tenta encontrar o breadcrumb homonimo
     * a rota atual
     *
     * @param string $name
     * @return Menu
     */
    public function breadcrumb(string $name = null) : Breadcrumb
    {
        if (! $name) {
            return $this->currentBreadcrumb();
        }

        return new Breadcrumb($name);
    }  

    /**
     * Retorna o breadcrumb de acordo com a rota atual
     *
     * @return void
     */
    private function currentBreadcrumb() : Breadcrumb
    {
        $route = $this->map()->current();
        
        return new Breadcrumb($route->name);
    }
}
