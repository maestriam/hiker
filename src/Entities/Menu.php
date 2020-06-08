<?php

namespace Maestriam\Hiker\Entities;

use Maestriam\Hiker\Entities\Map;
use Maestriam\Hiker\Exceptions\InvalidMenuNameException;
use Maestriam\Hiker\Exceptions\RouteNotFoundException;

class Menu
{
    private $map;

    private $name;

    private $collection = [];

    public function __get($name)
    {
        
    }

    /**
     * Inicializa os atributos mais importantes
     *
     * @param string $name
     */
    public function __construct(string $name = null)
    {
        $this->initialize()->setName($name)->load();
    }

    /**
     * Define o nome do menu
     *
     * @param string $name
     * @return Menu
     */
    private function setName(string $name) : Menu
    {
        if (strlen($name) == 0) {
            throw new InvalidMenuNameException();
        } 

        $this->name = $name;
        return $this;
    }

    /**
     * Inicializa o objeto com as regras de negócio
     * de pesquisa de rotas
     *
     * @return Menu
     */
    private function initialize() : Menu
    {
        if (! $this->map) {
            $this->map = new Map();
        }
    
        return $this;
    }

    private function load()
    {
        $routes = $this->map->groupedBy('menu', $this->name);

        $this->collection = $routes;
    }


    /**
     * Adiciona uma nova rota para o menu
     *
     * @param string $name
     * @return Menu
     */
    public function push(string $name) : Menu
    {
        $route = $this->map->find($name);

        if (! $route) {
            throw new RouteNotFoundException();
        }

        array_push($this->collection, $route);

        $key = key($this->collection);

        $route->register('menu', $this->name, ['order' => $key]);

        return $this;
    }

    /**
     * Retorna o menu com a sua collection completa
     *
     * @return void
     */
    public function get() : array
    {
        return $this->collection;
    }
}