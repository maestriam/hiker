<?php

namespace Maestriam\Hiker\Entities;

use Maestriam\Hiker\Entities\Foundation;
use Maestriam\Hiker\Exceptions\RouteNotFoundException;

class Breadcrumb extends Foundation
{
    /**
     * Nome do breadcrumb
     *
     * @var string
     */
    private $name = '';

    /**
     * Lista de rotas no breadcrumb
     *
     * @var array
     */
    private $collection = [];

    /**
     * Carrega os atributos e funções principais
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->setName($name);
    }

    /**
     * Atalho para resgate de atributos
     *
     * @param string $key
     * @return mixed
     */
    public function __get(string $key)
    {          
        return $this->getCustomAttribute($key);
    }

    /**
     * Retorna o nome do menu em questão
     *
     * @return string
     */
    protected function getName() : string
    {
        return $this->name;
    }

    /**
     * Retorna a coleção de rotas de uma classe
     *
     * @return array
     */
    protected function getCollection() : array
    {
        return $this->collection;
    }

    /**
     * Define o nome do breadcrumb
     *
     * @param string $name
     * @return Breadcrumb
     */
    private function setName(string $name) : Breadcrumb
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Adciona uma nova rota para o breadcrumb
     *
     * @param string $name
     * @param array $params
     * @return Breadcrumb
     */
    public function push(string $name, array $params = []) : Breadcrumb
    {
        $route = $this->map()->find($name, $params);
        
        if (! $route) {
            throw new RouteNotFoundException($name);
        }
        
        $current = $this->map()->current();

        $this->stack($route);
        return $this;
    }

    /**
     * 
     *
     * @param Route $item
     * @return Breadcrumb
     */
    private function stack(Route $route) : Breadcrumb
    {
        $this->collection[] = $route;
        return $this;
    }
}