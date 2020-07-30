<?php

namespace Maestriam\Hiker\Traits\Foundation;

use Maestriam\Hiker\Foundation\RouteMapper;

trait ManagesRoutes
{
    /**
     * Pesquisa de rotas
     *
     * @var RouteMapper
     */
    private static $mapInstance = null;
    
    /**
     * Retorna o singleton das RNs de busca de rota
     *
     * @return RouteMapper
     */
    public final function map() : RouteMapper
    {
        if (! self::$mapInstance) {
            self::$mapInstance = new RouteMapper();
        }

        return self::$mapInstance;
    }
}
