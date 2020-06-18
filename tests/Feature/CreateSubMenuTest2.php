<?php

namespace Maestriam\Hiker\Tests\Feature;

use Tests\TestCase;
use Maestriam\Hiker\Traits\Hikeable;
use Illuminate\Support\Facades\Route;

class CreateSubMenuTest2 extends TestCase
{
    use Hikeable;

    public function testCreateSubMenu()
    {
        Route::get('/',            ['as' => 'home']);
        Route::get('/blog',        ['as' => 'blog.index']);
        Route::post('/blog',       ['as' => 'blog.store']);
        Route::get('/blog/create', ['as' => 'blog.create']);
        Route::get('/blog/search', ['as' => 'blog.search']);

        $menu = $this->hiker()
                     ->menu('blog')
                     ->push('home')
                     ->sublevel('main')
                     ->push('blog.index')
                     ->push('blog.create')
                     ->sublevel('options')
                     ->push('blog.search')
                     ->push('blog.store');
        
        dd($menu);
    }
}


// @foreach ($sidebar->menu as $item) 

//     <label>$menu->icon $menu->label</label>

//     @if ($item->isRoute()) 

//         <a href="$item->url">$item->label </a>

//     @elseif ($item->isMenu()) 

//         <label>$item->icon $item->label</label>

//         @foreach ($item as $sub) 

//             <a href="$sub->url">$sub->label</a>

//         @endforeach

//     @endif 

// @endforeach