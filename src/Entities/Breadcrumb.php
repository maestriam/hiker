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
     * Rota que possuí o mesmo nome do bradcrumb
     *
     * @var Route
     */
    private $namesake = null;

    /**
     * Carrega os atributos e funções principais
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->setName($name)->findNamesake()->addLast()->load();
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
     * Tenta encontrar alguma rota homônima ao breadcrumb
     *
     * @return Breadcrumb
     */
    private function findNamesake() : Breadcrumb
    {
        $this->namesake = $this->map()->find($this->name);

        return $this;
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
     * Carrega as rotas vindas do cache
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
        $route = $this->route($name, $params);   

        if (! $route) {
            throw new RouteNotFoundException($name);
        }
        
        return $this->stack($route);
    }

    /**
     * Tenta encontra uma rota de acordo com o nome
     *
     * @param string $name
     * @return Route
     */
    private function route(string $name, array $params = []) : ?Route
    {
        return  $this->map()->find($name, $params);
    }

    /**
     * Salva a nova rota no breadcrumb
     *
     * @param Route $route
     * @return Breadcrumb
     */
    private function stack(Route $route) : Breadcrumb
    {
        if (! empty($this->collection)) {
            array_pop($this->collection);
        }

        return $this->add($route)->addLast()->update();
    }

    /**
     * Se o nome do breadcrumb for do mesmo nome de uma rota,
     * adiciona ele como último elemento do breadcrumb
     *
     * @return Breadcrumb
     */
    public function addLast() : Breadcrumb
    {
        if (! $this->namesake) {
            return $this;
        }
        
        return $this->add($this->namesake);
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
    private function update() : Breadcrumb
    {   
        $cache = $this->capsule()->encapsulate($this);

        $this->cache('breadcrumb')->update($this->name, $cache);

        return $this;
    } 
}