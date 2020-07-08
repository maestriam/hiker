<?php

namespace Maestriam\Hiker\Tests\Feature;

use Tests\TestCase;
use Maestriam\Hiker\Entities\Menu;
use Illuminate\Support\Facades\Route;
use Maestriam\Hiker\Traits\Support\Hikeable;

class CreateMenuTest extends TestCase
{
    use Hikeable;

    /**
     * Verifica se é possível criar um menu simples
     * com 3 elementos 
     *
     * @return void
     */
    public function testCreateMenu()
    {
        $this->createRoutes();

        $menu = $this->hiker()
                     ->menu('my-menu')
                     ->push('home.test')
                     ->push('blog.index')
                     ->push('blog.store')
                     ->get();

        $this->success($menu);
    }
    
    /**
     * Verifica a integridade do objeto Menu retornado pelo pacote
     *
     * @param mixed $menu
     * @return void
     */
    private function success($menu)
    {
        $this->assertInstanceOf(Menu::class, $menu);

        $this->contestAttributes($menu);
        $this->assertIsString($menu->name);
        $this->assertIsArray($menu->collection);
        $this->assertCount(3, $menu->collection);   
        
        foreach($menu->collection as $route) {
            $this->assertIsString($route->url);
        }
    }

    /**
     * Verifica se o objeto está trazendo todos os atributos principais
     *
     * @param Menu $menu
     * @return void
     */
    private function contestAttributes(Menu $menu)
    {
        $this->assertObjectHasAttribute('name', $menu);
        $this->assertObjectHasAttribute('collection', $menu);
    }

    /**
     * Função auxiliar para criar rotas para teste
     *
     * @return void
     */
    private function createRoutes()
    {
        Route::get('/',[
            'as' => 'home.test'
        ]);

        Route::get('/blog', [
            'as' => 'blog.index'
        ]);

        Route::post('/blog', [
            'as' => 'blog.store'
        ]);
    }
}
