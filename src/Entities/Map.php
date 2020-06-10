<?php

namespace Maestriam\Hiker\Entities;

use Maestriam\Hiker\Entities\Route;
use Illuminate\Routing\RouteCollection;
use Illuminate\Routing\Route as RoutingRoute;
use Illuminate\Support\Facades\Route as RoutingFacade;

class Map
{
    /**
     * Retorna todas as rotas cadastradas no sistema
     *
     * @return RouteCollection
     */
    public function all() : RouteCollection
    {
        return RoutingFacade::getRoutes();
    }

    /**
     * Retorna uma rota de acordo com seu nome definido
     *
     * @param string $name
     * @return Route|null
     */
    public function find(string $name) :?Route
    {
        $result = $this->search('as', $name); 

        return $result[0] ?? null;
    }

    /**
     * Retorna todos as  
     *
     * @param string $exp
     * @return array
     */
    public function readMenu(string $reg) : array
    {
        $finded  = [];
        $type    = 'menu';
        $pattern = sprintf('/%s./', $reg);

        foreach($this->all() as $source) {
            
            $actions = $source->getAction('hiker');
        
            if (! $actions || ! isset($actions[$type])) {
                continue;
            }

            foreach ($actions[$type] as $key => $action) {

                if (! preg_match($pattern, $key)) {
                    continue;
                }

                $finded[] = $key;
            }
        }

        return $finded;
    }

    /**
     * Retorna todas as rotas que possuem uma determinada 
     * propriedade registrada em suas actions
     *
     * @param string $prop
     * @return array
     */
    public function groupedBy(string $type, string $prop) : array
    {
        $finded = [];
        $all    = $this->all();

        foreach($all as $source) {

            if (! $source->getAction('hiker')) {
                continue;
            }

            $actions = $source->getAction('hiker');

            if (! isset($actions[$type][$prop])) {
                continue;
            }

            $finded[] = $this->objectify($source);
        }

        return $finded;
    }

    /**
     * Procura todas as rotas com um determinado 
     * par-valor em suas actions
     *
     * @param string $by
     * @param string $value
     * @return void
     */
    public function search(string $by, string $value) : array
    {   
        $collection = $this->all();
        $finded     = [];

        foreach ($collection as $source) {
                        
            if ($source->getAction($by) != $value) {
                continue;
            }

            $finded[] = $this->objectify($source);
        }

        return $finded;
    }

    /**
     * Retorna um objeto da
     *
     * @param RoutingRoute $route
     * @return void
     */
    private function objectify(RoutingRoute $source)
    {
        $route = new Route($source); 

        return $route;
    }
}