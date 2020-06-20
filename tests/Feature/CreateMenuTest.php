<?php

namespace Maestriam\Hiker\Tests\Feature;

use Illuminate\Support\Facades\Cache;
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
        $menu = $this->hiker()
                     ->menu('sidebar')
                     ->push('home')
                     ->push('blog.index')
                     ->push('blog.store')
                     ->get();

        $this->success($menu);
    }

    /**
     * Verifica se é possível resgatar 
     *
     * @return void
     */
    public function testRescueMenu()
    {
        $menu = $this->hiker()->menu('sidebar')->get();

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
            'as' => 'home'
        ]);

        Route::get('/blog', [
            'as' => 'blog.index'
        ]);

        Route::post('/blog', [
            'as' => 'blog.store'
        ]);
    }
}
