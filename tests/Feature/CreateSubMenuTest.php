<?php

namespace Maestriam\Hiker\Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Route;
use Maestriam\Hiker\Traits\Support\Hikeable;

class CreateSubMenuTest extends TestCase
{
    use Hikeable;

    public function testCreateSubMenu()
    {
        Route::get('/',      ['as' => 'home']);
        Route::get('/blog',  ['as' => 'blog.index']);
        Route::get('/theme', ['as' => 'theme.index']);
        Route::post('/blog', ['as' => 'blog.store']);

        $this->hiker()
             ->menu('submenus')
             ->push('blog.index')
             ->next('blog')
             ->push('blog.index')
             ->push('blog.store')
             ->back()
             ->next('theme')
             ->push('theme.index')
             ->back();

        $menu = $this->hiker()->menu('submenus')->get();

        foreach($menu->collection as $item) {

            if (! $item->isMenu()) {
                $this->assertIsString($item->url);
            
            } else {        
                foreach ($item->collection as $subitem) {
                    $this->assertIsString($subitem->url);
                }
            }            
        }
    }
}