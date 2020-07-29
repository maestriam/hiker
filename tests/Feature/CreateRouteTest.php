<?php

namespace Maestriam\Hiker\Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Str;
use Maestriam\Hiker\Support\Hiker;
use Illuminate\Support\Facades\Route;

class CreateRouteTest extends TestCase
{
    
    public function testCreateRoute()
    {
        $wow  = Str::random(3); 
        $icon = Str::random(6);
        $foo  = Str::random(226);
        $fly  = Str::random();

        Route::get('/get', [
            'as'   => 'test.get',
            'icon' => $icon,
            'foo'  => $foo,
            'wow'  => $wow,
        ]);   

        $breadcrumb = Hiker::breadcrumb('breadtest');
        $breadcrumb->push('test.get');

        $route = $breadcrumb->collection[0];
        $route->fly = $fly;

        $this->assertIsString($route->wow);
        $this->assertIsString($route->fly);

        $this->assertEquals($fly, $route->fly);
        $this->assertEquals($foo, $route->foo);
        $this->assertEquals($wow, $route->wow);
        $this->assertEquals($icon, $route->icon);
    }
    
}
