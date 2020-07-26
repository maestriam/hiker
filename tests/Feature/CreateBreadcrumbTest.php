<?php

namespace Maestriam\Hiker\Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Route;
use Maestriam\Hiker\Traits\Support\Hikeable;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateBreadcumbTest extends TestCase
{
    use Hikeable, WithoutMiddleware;

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

    public function testCreateWithParams()
    {
        Route::get('/', [
            'as' => 'test.index',
        ]);

        Route::get('/view/{id}', [
            'as' => 'test.view'
        ]);

        $this->get('/view/1');

        $breadcrumb = $this->hiker()->breadcrumb('test.view');   
        
        $breadcrumb->push('test.index')->push('test.view', ['id']);

        foreach ($breadcrumb->collection as $route) {
            $this->assertIsString($route->url);            
        }
    }
}