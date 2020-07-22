<?php

namespace Maestriam\Hiker\Entities;

use Maestriam\Hiker\Entities\Foundation;
use Maestriam\Hiker\Contracts\Navigator;
use Maestriam\Hiker\Exceptions\RouteNotFoundException;

class Breadcrumb extends Foundation implements Navigator
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
     * @param string  $name
     * @param array|null  $params
     * @return Breadcrumb
     * 
     * @throws RouteNotFoundException
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
     * Adiciona mais uma rota na pilha  
     *
     * @param Route $item
     * @return Breadcrumb
     */
    private function stack(Route $route) : Breadcrumb
    {
        $this->collection[] = $route;
        return $this;
    }

    /**
     * Salva o status atual do breadcrumb no cache
     *
     * @return Breadcrumb
     */
    private function save() : Breadcrumb
    {   
        $cache = $this->capsule()->encapsulate($this);

        return $this;
    } 
}