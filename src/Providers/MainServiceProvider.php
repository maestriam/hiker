<?php

namespace Maestriam\Hiker\Providers;

use Maestriam\Hiker\Entities\Hiker;
use Illuminate\Support\ServiceProvider;

class MainServiceProvider extends ServiceProvider
{
    /**
     * Registra o facade do Hiker
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('hiker',function() {
            return new Hiker();
        });
    }
}


