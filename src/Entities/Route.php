<?php

namespace Maestriam\Hiker\Entities;

use Illuminate\Routing\Route as RouteSource;

class Route
{
    private $source;

    private $label;
    
    /**
     * Instância 
     *
     * @param RouteSource $source
     */
    public function __construct(RouteSource $source = null, string $label = null)
    {
        $this->setSource($source)
             ->load()
             ->setLabel($label);
    }

    /**
     * Carrega as informações das rotas no objeto
     *
     * @return Menu
     */
    private function load() : Route
    {
        $actions = $this->source->getAction();
        
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
     * Define o nome que será exibido no
     *
     * @param string $name
     * @return void
     */
    private function setLabel(string $name = null)
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