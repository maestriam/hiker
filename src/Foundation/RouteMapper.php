<?php

namespace Maestriam\Hiker\Foundation;

use Maestriam\Hiker\Entities\Route;
use Illuminate\Routing\RouteCollection; 
use Illuminate\Routing\Route as RouteEntity;
use Illuminate\Support\Facades\Route as RoutingFacade;

class RouteMapper
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
     * Retorna um objeto de Rota do Hiker para o client
     *
     * @param RouteEntity $route
     * @return void
     */
    private function objectify(RouteEntity $source)
    {
        $route = new Route($source); 

        return $route;
    }
}