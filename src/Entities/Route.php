<?php

namespace Maestriam\Hiker\Entities;

use Illuminate\Routing\Route as RouteSource;

class Route
{
    private $source;
    
    private  $url;

    private $label;

    private $order;
    
    /**
     * Instância 
     *
     * @param RouteSource $source
     */
    public function __construct(RouteSource $source = null, string $label = null)
    {
        $this->setSource($source)
             ->loadBasic()
             ->label($label);
    }

    public function __get($name)
    {
        if ($name == 'url') {
            return $this->url;
        }
        
        elseif($name == 'label') {
            return $this->label;
        }

        elseif($name == 'order') {
            return $this->order;
        }
    }

    /**
     * Carrega as informações básicas das rota no objeto
     *
     * @return Menu
     */
    private function loadBasic() : Route
    {
        $actions = $this->source->getAction();
        
        $this->url = route($actions['as']);
        
        if (! isset($actions['hiker']['route'])) {
            return $this;
        }

        $props = $actions['hiker']['route']; 
        
        $this->label = $props['label'];

        return $this;
    }

    /**
     * Registra um valor nas propridades da Rota do Laravel
     *
     * @param string $col
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function register(string $col, string $name, $value)
    {
        $actions = $this->source->getAction();

        $actions['hiker'][$col][$name] = $value;

        $this->source->setAction($actions);
    }

    /**
     * Undocumented function
     *
     * @param string $name
     * @return void
     */
    public function loadMenu(string $name)
    {
        $attrs = $this->actions('menu', $name);

        $this->order = $attrs['order'];
    }

    /**
     * Undocumented function
     *
     * @param string $type
     * @param string $name
     * @return void
     */
    private function actions(string $type, string $name)
    {
        $actions = $this->source->getAction();

        return $actions['hiker'][$type][$name] ?? null;
    }

    /**
     * Define o nome que será exibido no
     *
     * @param string $name
     * @return void
     */
    public function label(string $name = null)
    {
        if (! strlen($name)) {
            return $this;
        }

        $this->register('route', 'label' , $name);

        $this->label = $name;
        return $this;
    }
        
    /**
     * Define qual as fontes de rotas do Laravel para o objeto
     *
     * @param RouteSource $route
     * @return Route
     */
    private function setSource(RouteSource $source = null) : Route
    {
        $this->source = $source;
        return $this;
    }
}