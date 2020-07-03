<?php

namespace Maestriam\Hiker\Entities;

use Maestriam\Hiker\Contracts\Navigator;
use Maestriam\Hiker\Entities\Foundation;
use Illuminate\Routing\Route as RouteSource;
use Maestriam\Hiker\Traits\Entities\SelfKnowledge;
use Maestriam\Hiker\Traits\Entities\CustomAttributes;

class Route extends Foundation implements Navigator
{
    use SelfKnowledge, CustomAttributes;

    /**
     * Rota do Laralve
     *
     * @var RouteSource
     */
    private $source;

    /**
     * Apelido atribuído a rota
     *
     * @var string
     */
    private $name = '';
    
    /**
     * Inicializa todos os atributos principais
     *
     * @param RouteSource $source
     */
    public function __construct(RouteSource $source = null, string $label = null)
    {
        $this->setSource($source)->load();
    }

    /**
     * Retorna um atributo do objeto
     *
     * @param string $key
     * @return mixed
     */
    public function __get(string $key)
    {
        return $this->getCustomAttribute($key);
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

    /**
     * Define o nome da rota no objeto
     *
     * @param string $name
     * @return Route
     */
    private function setName($val = null) :  Route
    {
        if (is_array($val) && isset($val['as'])) {
            $name = $val['as'];
        }

        $this->name = $name;
        return $this;
    }

    /**
     * Define a URL da rota
     *
     * @param string $url
     * @return Route
     */
    private function setUrl($val = null) : Route
    {
        if (is_array($val) && isset($val['as'])) {
            $url = route($val['as']);
        }

        $this->url = $url;
        return $this;
    }

    /**
     * Retorna o nome da rota 
     *
     * @return string
     */
    private function getName() : string
    {
        return $this->name; 
    }

    /**
     * Carrega as informações principais vindas da 
     * rota do Laravel
     *
     * @return void
     */
    private function load()
    {
        $actions = $this->source->getAction();

        $this->setName($actions)->setUrl($actions);
    }
}