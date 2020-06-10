<?php

namespace Maestriam\Hiker\Tests\Feature;

use Tests\TestCase;
use Maestriam\Hiker\Traits\Hikeable;
use Illuminate\Support\Facades\Route;

class CreateMenuTest extends TestCase
{
    use Hikeable;

    public function testCreateMenu()
    {
        Route::get('/',      ['as' => 'home']);
        Route::get('/blog',  ['as' => 'blog.index']);
        Route::post('/blog', ['as' => 'blog.store']);

        $response = $this->get('/');    

        $this->hiker()
             ->menu('sidebar')
             ->push('home')
             ->push('blog.index')
             ->push('blog.store')
             ->get();

        $menu = $this->hiker()->menu('sidebar')->get(); 

        foreach($menu as $route) {
            $this->assertIsString($route->url);
        }
    }

}
