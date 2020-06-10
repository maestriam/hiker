<?php

namespace Maestriam\Hiker\Tests\Feature;

use Tests\TestCase;
use Maestriam\Hiker\Traits\Hikeable;
use Illuminate\Support\Facades\Route;

class CreateSubMenuTest extends TestCase
{
    use Hikeable;

    public function testCreateSubMenu()
    {
        Route::get('/',            ['as' => 'home']);
        Route::get('/blog',        ['as' => 'blog.index']);
        Route::get('/blog/create', ['as' => 'blog.create']);
        Route::get('/blog/search', ['as' => 'blog.search']);
        Route::post('/blog',       ['as' => 'blog.store']);

        // $home = $this->hiker()->menu('sidebar');

        // $menu1 = $this->hiker()
        //               ->menu('home')
        //               ->push('blog.index') 
        //               ->push('blog.create')
        //               ->toArray();
        
        $menu2 = $this->hiker()
                      ->menu('blog')
                      ->push('home')
                      ->submenu('main')
                      ->push('blog.index')
                      ->push('blog.create')
                      ->submenu('options')
                      ->push('blog.search')
                      ->push('blog.store')
                      ->menu()
                      ->push('blog.store')
                      ->toArray();
                    //  ->push('blog.search');
                     //->toArray();

            
    }
}