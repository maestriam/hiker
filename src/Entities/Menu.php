<?php

namespace Maestriam\Hiker\Entities;

use Maestriam\Hiker\Entities\Map;
use Maestriam\Hiker\Exceptions\RouteNotFoundException;
use Maestriam\Hiker\Exceptions\InvalidMenuNameException;

class Menu
{
    /**
     * RNs de pesquisa de rotas e menus
     *
     * @var Map
     */
    private $map;

    /**
     * Nome do menu
     *
     * @var string
     */
    private $name = '';

    /**
     * Indica qual é o menu que está sendo utilizado
     * para adicionar rotas, no momento 
     *
     * @var string
     */
    private $current = '';
    
    /**
     * Indica quais são os menus que são vinculados 
     *
     * @var array
     */
    private $links = [];

    /**
     * Indica quais os submenus de um menu
     *
     * @var array
     */
    private $submenus = [];
    
    /**
     * Indica quais são as rotas do menu
     *
     * @var array
     */
    private $routes = [];

    /**
     * Inicializa os atributos principais para trabalhar 
     * com menus 
     *
     * @param string $name
     */
    public function __construct(string $name = null)
    {
        $this->setMap()
             ->setName($name)
             ->setCurrent()
             ->loadRoutes()
             ->loadSubmenus();
    }

    /**
     * Define o nome do menu que será trabalhado
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
     * de pesquisa de rotas e menus
     *
     * @return Menu
     */
    private function setMap() : Menu
    {
        if (! $this->map) {
            $this->map = new Map();
        }
    
        return $this;
    }

    /**
     * Carrega todos os submneus atributos ao menu principal
     *
     * @return Menu
     */
    private function loadSubmenus() : Menu
    {
        // $submenus = $this->map->readMenu($this->name);

        // if (empty($submenus)) {
        //     return $this;
        // }

        // foreach ($submenus as $submenu) {

        //     $sub = new Menu($submenu);
        //     $this->submenus[] = $sub;

        //     $x = $sub->toArray();

        //     dump($x);
        // }

        return $this;
    }

    /**
     * Carrega todas as rotas pertences ao menu
     *
     * @return Menu
     */
    private function loadRoutes() : Menu
    {
        // $routes = $this->map->groupedBy('menu', $this->name);

        // if (! $routes) {
        //     return $this;
        // }

        // foreach($routes as $route) {
        //     $this->routes[] = $route;
        // }

        return $this;
    }

    /**
     * Define um novo submenu para o menu
     *
     * @param string $name
     * @return void
     */
    public function submenu(string $name)
    {
        // if (! in_array($name, $this->submenus)) {
        //     $this->submenu[] = $name;
        // }

        $this->setCurrent($name);

        return $this;
    }

    /**
     * Retorna a lista de todos os submenus
     *
     * @return array
     */
    public function submenus() : array
    {
        return $this->submenus;
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

        $this->stack($route);
        
        return $this;
    }
    
    /**
     * Registra e coloca uma nova rota na pilha de um menu/submenu
     *
     * @param Route $route
     * @return Menu
     */
    private function stack(Route $route) : Menu
    {
        $value = [$this->current => $route];

        $this->routes[] = $value;
        
        $key = array_search($value, $this->routes);
        
        $attrs = ['order' => $key];
        
        $route->register('menu', $this->current, $attrs);

        return $this;
    }

    /**
     * Define qual será que o menu que s
     *
     * @return string
     */
    private function setCurrent(string $name = null) : Menu
    {
        $current = (! $name) ? $this->name : 
                               $this->name . '.' . $name;

        $this->current = $current;

        return $this;
    }

    public function menu()
    {
        $this->setCurrent();
        return $this; 
    }

    public function toArray() 
    {
        foreach($this->routes as $route) {

            foreach ($route as $k => $v) {
                
                dump($k . '= '. $v->url);
            }
        }
    }
}