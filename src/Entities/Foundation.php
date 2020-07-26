<?php

namespace Maestriam\Hiker\Entities;

use Maestriam\Hiker\Foundation\NavEncapsulator;
use Maestriam\Hiker\Traits\Foundation\ManagesCache;
use Maestriam\Hiker\Traits\Foundation\ManagesRoutes;
use Maestriam\Hiker\Traits\Foundation\CustomizesAttributes;
use Maestriam\Hiker\Traits\Foundation\IdentifiesEntities;
use Maestriam\Hiker\Foundation\SessionHandler;

class Foundation
{
    use CustomizesAttributes, 
        IdentifiesEntities, 
        ManagesCache,  
        ManagesRoutes;

    /**
     * Instancia de conversão de array/objeto
     *
     * @var NavEncapsulator
     */
    private static $capsuleInstance = null;

    /**
     * Instancia de conversão de array/objeto
     *
     * @var NavEncapsulator
     */
    private static $sessionInstance = null;

    /**
     * Retorna o singleton das  RNs de encapsulamento de entidades
     *
     * @return NavEncapsulator
     */
    protected function capsule() : NavEncapsulator
    {
        if (! self::$capsuleInstance) {
            self::$capsuleInstance = new NavEncapsulator();
        }

        return self::$capsuleInstance;
    }

    /**
     * Retorna o singleton das  RNs de sessão de entidades
     *
     * @return NavEncapsulator
     */    
    protected function session() : SessionHandler
    {
        if (! self::$sessionInstance) {
            self::$sessionInstance = new SessionHandler();
        }

        return self::$sessionInstance;
    }

}