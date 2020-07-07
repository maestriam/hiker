<?php

namespace Maestriam\Hiker\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class MainServiceProvider extends ServiceProvider
{
    /**
     * Ao iniciar o service provider...
     *
     * @return void
     */
    public function boot()
    {

    }

    public function menu(string $name)
    {
        //$menu = new Menu();   
    }
}


