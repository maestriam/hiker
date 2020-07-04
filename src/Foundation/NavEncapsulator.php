<?php

namespace Maestriam\Hiker\Foundation;

use Maestriam\Hiker\Entities\Menu;
use Maestriam\Hiker\Entities\Map;
use Maestriam\Hiker\Entities\Route;
use Maestriam\Hiker\Contracts\Navigator;

/**
 * Classe responsável por encapsular/descapsular os objetos 
 * das entidades do sistemas, em objetos em arrays e vice-versa
 */
class NavEncapsulator
{
    private $mapInstance = null;

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
     * Undocumented function
     *
     * @return Map
     */
    private function map() : Map
    {
        if ($this->mapInstance == null) {
            $this->mapInstance = new Map();
        }

        return $this->mapInstance;
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    private function rootCapsule(Menu $root) : array
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
                                    
            $capsule = ($item instanceof Menu) ? 
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
        $name  = $item['name']; 
        $route = $this->map()->find($name);

        return $route;
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
        $menu = new Menu($name);

        return $menu;
    }
}