<?php

namespace Maestriam\Hiker\Entities;

use Maestriam\Hiker\Foundation\RouteMapper;
use Maestriam\Hiker\Foundation\CacheHandler;
use Maestriam\Hiker\Foundation\CustomAttribute;
use Maestriam\Hiker\Foundation\NavEncapsulator;

class Foundation
{
    /**
     * Instância de cache
     *
     * @var CacheHandler
     */
    private static $cacheInstance = null;

    /**
     * Pesquisa de rotas
     *
     * @var RouteMapper
     */
    private static $mapInstance = null;

    /**
     * Instancia de conversão de array/objeto
     *
     * @var NavEncapsulator
     */
    private static $capsuleInstance = null;

    /**
     * Atributos personalizados
     *
     * @var NavEncapsulator
     */
    private static $customInstance = [];

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

    /**
     * Retorna o singleton das  RNs de encapsulamento de entidades
     *
     * @return NavEncapsulator
     */
    public function capsule() : NavEncapsulator
    {
        if (! self::$capsuleInstance) {
            self::$capsuleInstance = new NavEncapsulator();
        }

        return self::$capsuleInstance;
    }

    /**
     * Retorna o singleton das  RNs de atributos customizados
     *
     * @return CustomAttribute
     */
    public function custom() : CustomAttribute
    {
        if (! isset(self::$customInstance[$this->name])) {
            self::$customInstance[$this->name] = new CustomAttribute();
        }

        return self::$customInstance[$this->name];
    }
}