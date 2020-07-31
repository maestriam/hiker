<?php

namespace Maestriam\Hiker\Foundation;

use Maestriam\Hiker\Entities\Menu;
use Maestriam\Hiker\Entities\Route;
use Maestriam\Hiker\Contracts\Navigator;
use Maestriam\Hiker\Traits\Foundation\ManagesRoutes;

/**
 * Classe responsável por encapsular/descapsular os objetos 
 * das entidades do sistemas, em objetos em arrays e vice-versa
 */
class NavEncapsulator
{
    use ManagesRoutes;

    /**
     * Retorna a forma encapsulada de um objeto de navegação
     *
     * @param Navigator $nav
     * @return array
     */
    public function encapsulate(Navigator $nav) : array
    {
        return $this->rootCapsule($nav);
    }

    /**
     * Retorna o array com as informações principais do 
     * menu
     *
     * @return array
     */
    private function rootCapsule(Navigator $root) : array
    {
        $collection = $this->collectionCapsule($root->collection);
        
        $capsule = [
            'attributes' => $root->attributes,
            'collection' => $collection
        ];

        return $capsule;
    }    
    
    /**
     * Retorna a forma encapsulada das informações
     * dos objetos de rota e menu para array
     *
     * @return array
     */
    private function collectionCapsule(array $collection) : array
    {
        $capsule  = null;
        $bottle  = [];

        foreach ($collection as $item) {
        
            $capsule = ($item->isMenu()) ? 
                        $this->menuCapsule($item) :
                        $this->routeCapsule($item);

            $bottle[] = $capsule;
        }

        return $bottle;
    }

    /**
     * Extrai os dados essênciais do menu e converte
     * em array para armazamento em cache
     *
     * @param Menu $item
     * @return array
     */
    private function menuCapsule(Menu $item) : array
    {
        $capsule = [
            'type'       => 'menu',
            'name'       => $item->name,    
            'attributes' => $item->attributes,
        ];
    
        return $capsule;
    }

    /**
     * Extrai os dados essênciais da rota e converte
     * em array para armazamento em cache
     * 
     * @param Route $item
     * @return array
     */
    private function routeCapsule(Route $item) : array
    {
        return [
            'type'       => 'route',
            'name'       => $item->name,
            'params'     => $item->params,
            'attributes' => $item->attributes,
        ];
    }

    /**
     * Retorna uma instância de um objeto Navigator
     *
     * @param array $capsule
     * @return Navigator
     */
    public function expand(array $capsule) : ?Navigator
    {
        if ($capsule['type'] == 'route') {
            return $this->parseRoute($capsule);
        }

        return $this->parseMenu($capsule);
    }

    /**
     * Interpreta as informações básicas da rota
     * e converte em um objeto Route
     *
     * @param array $item
     * @return void
     */
    private function parseRoute(array $item) : ?Route
    {
        $name   = $item['name']; 
        $params = $item['params'] ?? [];

        return $this->map()->find($name, $params);
    }

    /**
     * Interpreta as informações básicas do menu
     *
     * @param array $item
     * @return void
     */
    private function parseMenu(array $item) : Menu
    {
        $name = $item['name'];

        return new Menu($name);
    }
}