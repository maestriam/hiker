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
        Route::get('/',      ['as' => 'home']);
        Route::get('/blog',  ['as' => 'blog.index']);
        Route::get('/theme', ['as' => 'theme.index']);
        Route::post('/blog', ['as' => 'blog.store']);

        $this->hiker()
             ->menu('sidebar')
             ->push('blog.index')
             ->sub('blog')
             ->push('blog.index')
             ->push('blog.store')
             ->back()
             ->sub('theme')
             ->push('theme.index')
             ->back();

        $menu = $this->hiker()->menu('sidebar')->get();

        foreach($menu->collection as $item) {

            if (! $item->isMenu()) {
                dump($item->url);
                continue;
            }

            foreach ($item->collection as $subitem) {
                dump($subitem->url);
            }
        }
    }
}