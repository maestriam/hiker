<?php

namespace Maestriam\Hiker\Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Route;
use Maestriam\Hiker\Traits\Support\Hikeable;

class CreateBreadcumbTest extends TestCase
{
    use Hikeable;

    public function testCreateBreadcrumb()
    {
        Route::get('/', [
            'as' => 'test.index',
        ]);

        Route::get('/show', [
            'as' => 'test.show',
        ]);

        $breadcrumb = $this->hiker()->breadcrumb('test.show');            
    
        $breadcrumb->push('test.index')
                   ->push('test.show');

        foreach ($breadcrumb->collection as $route) {
            $this->assertIsString($route->url);            
        }
    }
}