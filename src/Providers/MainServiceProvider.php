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
        Route::get('/home', [
            'as'    => 'home',
            'menus' => ['sidebar' => 1],
            'uses' => 'App\Http\Controllers\Index@index'
        ]);

        Route::post('/blog', [
            'as'   => 'blog.store',
            'uses' => 'App\Http\Controllers\Index@index'
        ]);

        Route::get('/blog', [
            'as'   => 'blog.index',
            'uses' => 'App\Http\Controllers\Index@index'
        ]);

        // $this->menu('sidebar')
        //      ->push('home')
        //      ->push('blog.index')
        //      ->push('blog.store');

        // $collection = Route::getRoutes();

    }

    public function menu(string $name)
    {
        //$menu = new Menu();   
    }
}


