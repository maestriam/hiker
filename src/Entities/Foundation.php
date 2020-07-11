<?php

namespace Maestriam\Hiker\Entities;

use Maestriam\Hiker\Foundation\NavEncapsulator;
use Maestriam\Hiker\Traits\Foundation\ManagesCache;
use Maestriam\Hiker\Traits\Foundation\ManagesRoutes;
use Maestriam\Hiker\Traits\Foundation\CustomizesAttributes;
use Maestriam\Hiker\Traits\Foundation\IdentifiesEntities;

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

}