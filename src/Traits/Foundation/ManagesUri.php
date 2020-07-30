<?php

namespace Maestriam\Hiker\Traits\Foundation;

use Maestriam\Hiker\Foundation\UriParser;

/**
 * Cria uma instância das 
 */
trait ManagesUri
{
    private static $uriInstance = null;

    /**
     * Retorna uma instância com as RNs para interpretação de URI
     *
     * @return UriParser
     */
    public final function uri() : UriParser 
    {
        if (! self::$uriInstance) {
            self::$uriInstance = new UriParser();
        }

        return self::$uriInstance;
    }
}
