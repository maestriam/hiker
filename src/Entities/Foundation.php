<?php

namespace Maestriam\Hiker\Entities;

use Maestriam\Hiker\Foundation\RouteMapper;
use Maestriam\Hiker\Foundation\CacheHandler;

class Foundation
{
    /**
     * Instância de cache
     *
     * @var CacheHandler
     */
    private static $cacheInstance = null;

    /**
     * Instância de route
     *
     * @var RouteMapper
     */
    private static $mapInstance = null;

    /**
     * Retorna o singleton das RNs de cache
     *
     * @return void
     */
    public function cache(string $name) : CacheHandler
    {
        if (! self::$cacheInstance) {
            self::$cacheInstance = new CacheHandler();
        }

        return self::$cacheInstance->subject($name);
    }

    /**
     * Retorna o singleton das RNs de busca de rota
     *
     * @return RouteMapper
     */
    public function map() : RouteMapper
    {
        if (! self::$mapInstance) {
            self::$mapInstance = new RouteMapper();
        }

        return self::$mapInstance;
    }
}