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
        $this->setName($name)->load();
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
     * Carrega
     *
     * @return void
     */
    private function load() : Breadcrumb
    {
        $cache = $this->cache('breadcrumb')->get($this->name);

        if (empty($cache) || ! $cache) {
            return $this;
        }

        $this->custom()->load($cache['attributes']);
        
        $this->parse($cache['collection']);

        return $this;
    }

    /**
     * Percorre todos os registros de rotas
     * para pegar as rotas 
     *
     * @param array $collection
     * @return void
     */
    private function parse(array $collection)
    {
        $route = null;

        foreach ($collection as $item) {
            $route = $this->capsule()->expand($item);
            $this->add($route);
        }

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

        return $this->stack($route);
    }

    /**
     * Salva a nova rota no breadcrumb
     *
     * @param Route $route
     * @return Breadcrumb
     */
    private function stack(Route $route) : Breadcrumb
    {
        return $this->add($route)->save();
    }

    /**
     * Adiciona mais uma rota na pilha  
     *
     * @param Route $item
     * @return Breadcrumb
     */
    private function add(Route $route) : Breadcrumb
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

        $this->cache('breadcrumb')->update($this->name, $cache);

        return $this;
    } 
}