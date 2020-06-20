<?php

namespace Maestriam\Hiker\Entities;

use Maestriam\Hiker\Entities\Foundation;
use Maestriam\Hiker\Traits\Entities\SelfKnowledge;
use Maestriam\Hiker\Traits\Entities\MagicMethods;
use Maestriam\Hiker\Exceptions\RouteNotFoundException;

class Menu extends Foundation
{
    use SelfKnowledge, MagicMethods;

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
        return $this->getAttribute($key);
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
     * 
     */
    private function getParent() : ?Menu
    {
        return $this->parent;
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
    public function next(string $name) : Menu
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
     * Extrai os dados essênciais do menu e converte
     * em array para armazamento em cache
     *
     * @param Menu $item
     * @return array
     */
    private function mountMenu(Menu $item) : array
    {
        $parent = $this->getParent();

        return [
            'type'   => 'menu',
            'name'   => $item->name,
            'parent' => $parent->name ?? null
        ];
    }

    /**
     * Extrai os dados essênciais da rota e converte
     * em array para armazamento em cache
     * 
     * @param Route $item
     * @return array
     */
    private function mountRoute(Route $item) : array
    {
        return [
            'type' => 'route',
            'name' => $item->name,
        ];
    }

    /**
     * Salva o status atual do menu no cache
     *
     * @return void
     */
    private function save()
    {        
        $array = $this->collectionToArray();

        $this->cache('menu')->store($this->name, $array);

        return $this;
    }

    /**
     * Retorna a forma fragmentada das informações
     * dos objetos de rota e menu para array
     *
     * @return array
     */
    private function collectionToArray() : array
    {
        $value  = null;
        $scraps = [];

        foreach ($this->collection as $item) {
                                    
            if ($item instanceof Menu) {
                $value = $this->mountMenu($item);        
            } else {
                $value = $this->mountRoute($item);
            }

            $scraps[] = $value;
        }

        return $scraps;
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

        foreach ($cache as $item) {
            $this->parse($item);
        }
    }

    /**
     * Interpreta as informações básicas do 
     *
     * @param array $item
     * @return void
     */
    private function parse(array $item)
    {
        if ($item['type'] == 'menu') {
            return $this->parseMenu($item);
        }

        return $this->parseRoute($item);
    }

    /**
     * Interpreta as informações básicas da rota
     * e converte em um objeto Route
     *
     * @param array $item
     * @return void
     */
    private function parseRoute(array $item)
    {
        $name  = $item['name']; 
        $route = $this->map()->find($name);

        if (! $route) {
            return null;
        }

        $this->add($route);
    }

    /**
     * Interpreta as informações básicas do menu
     * e converte em um objeto Menu
     *
     * @param array $item
     * @return void
     */
    private function parseMenu(array $item)
    {
        $name   = $item['name'];
        $parent = $item['parent'];
        
        $menu = new Menu($name);
        $menu->setParent($parent);

        $this->add($menu);
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