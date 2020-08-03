<?php

namespace Maestriam\Hiker\Foundation;

use Maestriam\Hiker\Entities\Route;
use Illuminate\Routing\RouteCollection;
use Illuminate\Routing\Route as RouteEntity;
use Illuminate\Support\Facades\Route as RoutingFacade;

class RouteMapper
{
    /**
     * Paramêtros auxiliar para geração da rota
     *
     * @var array
     */
    private $params = [];

    /**
     * Define os paramêtros complementares para gerar URL
     *
     * @param array $params
     * @return RouteMapper
     */
    private function setParams(array $params) : RouteMapper
    {
        $this->params = $params;
        return $this;
    }

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
     * Retotna a instância da rota atual 
     *
     * @return \Maestriam\Hiker\Entities\Route
     */
    public function current() : ?Route
    {
        $source = RoutingFacade::current();

        if (! $source) {
            return null;
        }

        return $this->objectify($source);
    }

    /**
     * Retorna uma rota de acordo com seu nome definido
     *
     * @param string $name
     * @return Route|null
     */
    public function find(string $name, array $params = []) :?Route
    {
        $this->setParams($params);

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
        $route = new Route($source, $this->params); 

        return $route;
    }
}