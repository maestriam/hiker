<?php

namespace Maestriam\Hiker\Entities;

use Maestriam\Hiker\Entities\Foundation;
use Maestriam\Hiker\Contracts\Navigator;
use Maestriam\Hiker\Traits\Entities\SelfKnowledge;
use Maestriam\Hiker\Traits\Entities\CustomAttributes;
use Maestriam\Hiker\Exceptions\RouteNotFoundException;

class Menu extends Foundation implements Navigator
{
    use SelfKnowledge, CustomAttributes;

    /**
     * Nome do menu
     *
     * @var string
     */
    private $name = '';

    /**
     * Coleção de rotas e sub-menus 
     *
     * @var array
     */
    private $collection = [];

    /**
     * Define qual será o menu-pai (em caso de sub-menu)
     *
     * @var Menu
     */
    private $parent = null;

    /**
     * Inicializa os atributos principais
     *
     * @param string $name
     * @param Menu $parent
     */
    public function __construct(string $name, Menu $parent = null)
    {   
        $this->setName($name)
             ->setParent($parent)
             ->load();
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
     * Retorna a instância com as propriedades definidas do menu
     *
     * @return Menu
     */
    public function get() : Menu
    {
        return $this;
    }

    /**
     * Retorna o nome do menu em questão
     *
     * @return string
     */
    private function getName() : string
    {
        return $this->name;
    }

    /**
     * Retorna a coleção de rotas e submenus
     *
     * @return array
     */
    private function getCollection() : array
    {
        $this->cache('menu')->destroy($this->name);

        return $this->collection;
    }

    /**
     * Define o nome do menu
     *
     * @param string $name
     * @return Menu
     */
    private function setName(string $name) : Menu
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Define o menu-pai do menu
     *
     * @param Menu $parent
     * @return Menu
     */
    private function setParent($parent = null) : Menu
    {
        if (! $parent) {
            return $this;
        }

        $this->parent = $parent;
        return $this;
    }
    
    /**
     * Inicializa a coleção de rotas e sub-menus
     *
     * @param mixed $collection
     * @return void
     */
    private function setCollection($collection) : Menu
    {
        if (! $collection) {
            $collection = [];
        }

        $this->collection = $collection;
        return $this;
    }

    /**
     * Cria uma nova instância de menu
     * para gerar um sub-menu
     *
     * @param string $name
     * @return Menu
     */
    public function next(string $name) : Menu
    {
        $name   = $this->name . '-' . $name; 
        $finded = $this->find($name);
        
        if ($finded) {
            return $finded;
        }
        
        $submenu = new Menu($name, $this);
        
        $this->stack($submenu);

        return $submenu;
    }

    /**
     * Retorna um submenu pelo nome
     *
     * @param string $name
     * @return Menu|null
     */
    private function find(string $name) : ?Menu
    {
        foreach($this->collection as $item) {

            if (! $item->isMenu()) {
                continue;
            }

            if ($item->name == $name) {
                return $item;
            }
        }

        return null;
    } 

    /**
     * Adiciona uma nova rota na coleção do menu
     *
     * @param string $name
     * @return Menu
     */
    public function push(string $name) : Menu
    {
        $route = $this->map()->find($name);

        if (! $route) {
            throw new RouteNotFoundException();
        }

        $this->stack($route);

        return $this;
    }

    /**
     * Adiciona um novo item na coleção
     *
     * @param mixed $item
     * @return Menu
     */
    private function stack($item) : Menu
    {
        $this->add($item)->save();

        return $this;
    }

    /**
     * Adiciona o item no array de coleção
     *
     * @param array $item
     * @return void
     */
    private function add($item) : Menu
    {
        $this->collection[] = $item;
        return $this;
    }

    /**
     * Salva o status atual do menu no cache
     *
     * @return void
     */
    private function save() : Menu
    {        
        $cache = $this->capsule()->encapsulate($this);  

        $this->cache('menu')->update($this->name, $cache);

        return $this;
    }
    
    /**
     * Retorna o menu-pai do menu
     *
     * @return Menu
     */
    public function back() : Menu 
    {
        $instance = $this->parent ?? $this; 
        return $instance;
    }

    /**
     * Carrega as informações salvas do cache sobre o menu
     *
     * @return void
     */
    private function load()
    {
        $cache = $this->cache('menu')->get($this->name);

        if (empty($cache) || ! $cache) {
            return null;
        }

        $this->custom()->load($cache['attributes']);

        $this->parseCollection($cache['collection']);
    }
        
    /**
     * Define valor de um atributo definido pelo usuário. 
     * Se o usuário não passar o valor, retorna o valor definido.
     *
     * @param string $key
     * @param string $value
     * @return Menu
     */
    public final function attr(string $key, string $value) : Menu
    {
        $this->custom()->set($key, $value);
        
        return $this->save();
    }
    
    /**
     * Interpreta as informações resumidas 
     *
     * @param array $collection
     * @return Menu
     */
    private function parseCollection($collection) : Menu
    {        
        if (empty($collection) || ! is_array($collection)) {
            return $this;
        }

        foreach ($collection as $item) {

            $nav = $this->capsule()->expand($item);

            if ($nav instanceof Menu) {
                $nav->setParent($this);
            }

            $this->add($nav);
        }

        return $this;
    }
} 