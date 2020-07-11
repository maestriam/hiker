<?php

namespace Maestriam\Hiker\Traits\Foundation;

use Maestriam\Hiker\Foundation\CacheHandler;

trait ManagesCache
{
    /**
     * Instância de cache
     *
     * @var CacheHandler
     */
    private static $cacheInstance = null;

    /**
     * Retorna o singleton das RNs de cache
     *
     * @return CacheHandler
     */
    public function cache(string $subject = null) : CacheHandler
    {
        if (! self::$cacheInstance) {
            self::$cacheInstance = new CacheHandler();
        }

        if ($subject) {
            self::$cacheInstance->subject($subject);
        }

        return self::$cacheInstance;
    }
}