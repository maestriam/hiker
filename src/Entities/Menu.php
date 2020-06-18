<?php

namespace Maestriam\Hiker\Entities;

use Maestriam\Hiker\Entities\Map;
use Maestriam\Hiker\Entities\Foundation;
use Maestriam\Hiker\Traits\Entities\SelfKnowledge;
use Maestriam\Hiker\Exceptions\RouteNotFoundException;
use Maestriam\Hiker\Exceptions\InvalidMenuNameException;

class Menu extends Foundation
{
    use SelfKnowledge;

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
        return $this->setName($name)
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
        if ($key == 'name') {
            return $this->getName();
        }
        
        elseif($key == 'collection') {
            return $this->getCollection();
        }

        return null;
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

        if (is_string($parent)) {
            $parent = new Menu($parent);
        }

        $this->parent = $parent;
        return $this;
    }

    /**
     * Cria uma nova instância de menu
     * para gerar um sub-menu
     *
     * @param string $name
     * @return Menu
     */
    public function sub(string $name) : Menu
    {
        $name   = $this->name . '.' . $name; 
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
        $this->collection[] = $item;

        $this->save();

        return $this;
    }

    /**
     * Salva o status atual do menu no cache
     *
     * @return void
     */
    private function save()
    {        
        $name = ($this->parent) ? $this->parent->getName() : null;

        $val = [
            'parent'     => $name,
            'collection' => $this->collection, 
        ];

        $this->cache('menu')->store($this->name, $val);
        
        return $this;
    }

    /**
     * Carrega as informações salvas do cache sobre o menu
     *
     * @return void
     */
    private function load()
    {
        $cache = $this->cache('menu')->get($this->name);
        
        $this->setCollection($cache['collection'])
             ->setParent($cache['parent']);
             
    }

    public function get()
    {
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
     * Retorna o menu-pai do menu
     *
     * @return Menu
     */
    public function back() : Menu 
    {
        $instance = $this->parent ?? $this; 
        return $instance;
    }
}