<?php

namespace Maestriam\Hiker\Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Route;
use Maestriam\Hiker\Traits\Support\Hikeable;
use Maestriam\Hiker\Exceptions\RouteNotFoundException;

class CreateBreadcumbTest extends TestCase
{
    use Hikeable;
    
    /**
     * Verifica se consegue fazer um breadcrumb simples 
     *
     * @return void
     */
    public function testCreateBreadcrumb()
    {
        $this->createRoutes();

        $breadcrumb = $this->hiker()
                           ->breadcrumb('test.show')
                           ->push('test.index');            
    
        $collection = $breadcrumb->collection;
        
        $this->assertCount(2, $collection);

        foreach ($collection as $route) {
            $this->assertIsString($route->url);    
            $this->assertRouteUrl($route->name, $route->url);        
        }
    }

    /**
     * Verifica se consegue fazer um breadcrumb com parâmetros deduzidos 
     *
     * @return void
     */
    public function testCreateWithParams()
    {
        $this->createRoutes();
        
        $this->get('/view/1');        

        $breadcrumb = $this->hiker()
                           ->breadcrumb('test.view')
                           ->push('test.index');  
        
        $collection = $breadcrumb->collection;
        
        $this->assertCount(2, $collection);
    
        $this->contestCollection($collection);
    }

    /**
     * Verifica se é possível criar um breadcrumb homonimo a uma rota
     *
     * @return void
     */
    public function testNamesakeBreadcrumb()
    {
        $this->createRoutes();
        
        $breadcrumb = $this->hiker()->breadcrumb('test.index');
        $collection = $breadcrumb->collection;

        $this->assertCount(1, $collection);

        $this->contestCollection($collection);
    }

    /**
     * Verifica se é possível criar um breadcrumb com uma rota inválida
     *
     * @return void
     */
    public function testInvalidRoute()
    {
        $this->createRoutes();

        $this->expectException(RouteNotFoundException::class);

        $breadcrumb = $this->hiker()
                           ->breadcrumb('test.show')
                           ->push('f');
    }

    /**
     * Verifica se é possível criar um breadcrumb com um nome qualquer,
     * sem vínculo com qualquer rota
     *
     * @return void
     */
    public function testCreateWithRandomName()
    {
        $this->createRoutes();

        $breadcrumb = $this->hiker()->breadcrumb('foo');

        $this->assertCount(0, $breadcrumb->collection);
        
        $breadcrumb->push('test.index');

        $this->assertCount(1, $breadcrumb->collection);
    }

    /**
     * Verifica se o breadcrumb está considerando sensitive-case
     *
     * @return void
     */
    public function testSensitiveCase()
    {
        $this->createRoutes();

        $breadcrumb = $this->hiker()
                           ->breadcrumb('foo')
                           ->push('test.index');

        $this->assertCount(1, $breadcrumb->collection);
        
        $breadcrumb = $this->hiker()->breadcrumb('Foo');

        $this->assertCount(0, $breadcrumb->collection);
    }

    /**
     * Verifica a integridade da collection
     *
     * @param array $collection
     * @return void
     */
    private function contestCollection(array $collection)
    {
        foreach($collection as $route) {
            $this->assertIsString($route->url);    
            $this->assertRouteUrl($route->name, $route->url);
        }
    }

    /**
     * Registra as rotas para teste
     *
     * @return void
     */
    private function createRoutes()
    {
        Route::get('/', [
            'as' => 'test.index',
        ]);

        Route::get('/show', [
            'as' => 'test.show',
        ]);
        
        Route::get('/view/{id}', [
            'as' => 'test.view'
        ]);
    }

    /**
     * Verifica se as rotas são iguais
     *
     * @param string $name
     * @param string $url
     * @return void
     */
    private function assertRouteUrl(string $name, string $url, string $uri = '') : void
    {
        $routes =  [
            'test.index' => 'http://localhost',
            'test.show'  => 'http://localhost/show',
            'test.view'  => 'http://localhost/view/1',
        ];

        $expected = $routes[$name] . $uri;       

        $this->assertEquals($expected, $url);
    }
}