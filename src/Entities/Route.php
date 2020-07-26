<?php

namespace Maestriam\Hiker\Entities;

use Maestriam\Hiker\Contracts\Navigator;
use Maestriam\Hiker\Entities\Foundation;
use Illuminate\Support\Facades\Session;
use Illuminate\Routing\Route as RouteSource;
use Illuminate\Support\Facades\Route as RouteFacade;
use stdClass;

class Route extends Foundation implements Navigator
{
    /**
     * Objeto Laravel com as propriedades da rota
     *
     * @var RouteSource
     */
    private $source;

    /**
     * Parâmetros necessários para montar a URL da rota
     *
     * @var array
     */
    private $params = [];

    /**
     * Nome para identificação da rota
     *
     * @var string
     */
    private $name = '';
    
    /**
     * Inicializa todos os atributos principais
     *
     * @param RouteSource $source
     */
    public function __construct(RouteSource $source = null, array $params = [])
    {
        $this->setSource($source)
             ->setName()
             ->setParams($params)
             ->setUrl();
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
     * Define os parâmetros necessários para montar a URL da rota
     *
     * @param array $params
     * @return Route
     */
    private function setParams(array $params) : Route
    {
        foreach ($params as $k => $v) {            
            
            $param = $this->prepare($k, $v);     
            
            $this->stack($param)->note($param->key, $param->value);
        }

        return $this;
    }

    /**
     * Prepara os parâmetros necessários para montar a rota
     * Se algum paramêtro passado não tiver o valor definido,
     * verifica qual foi o ultimo valor recebido ou se o valor está
     * na URL atual
     *
     * @param mixed $key
     * @param mixed $value
     * @return stdClass
     */
    private function prepare($key, $value) : stdClass
    {
        if (! $this->isAssocArray($key)) {
            $key   = $value;
            $value = ($this->isCurrent()) ? request($key) : $this->last($key);                                     
        }
        
        $obj = ['key' => $key, 'value' => $value];
        
        return (object) $obj;                
    }

    /**
     * Adiciona um novo item para a pilha de parâmetros
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    private function stack(stdClass $param) : Route
    {
        $key   = $param->key;
        $value = $param->value;

        $this->params[$key] = $value;
        return $this;
    }

    /**
     * Retorna o último o valor de um parâmetro visto
     *
     * @param string $key
     * @return void
     */
    private function last(string $key)
    {
        return $this->session()->tag($this->name)->get($key);
    }

    /**
     * Registra o valor do parâmetro utilizado na rota
     *
     * @param string $key
     * @return string|null
     */
    private function note(string $key, $value = null) 
    {
        if (! $value)  return null;

        return $this->session()->tag($this->name)->put($key, $value);
    }


    /**
     * Verifica se o parâmetro passado é um array associativo
     * Se for, significa que foi passado o paramêtro par-valor
     *
     * @param array $array
     * @return boolean
     */
    private function isAssocArray($key) : bool
    {
        return (is_numeric($key)) ? false : true;
    }
   
    /**
     * Define o nome da rota no objeto
     *
     * @param string $name
     * @return Route
     */
    private function setName($name = null) :  Route
    {
        if (! $name) {
            $actions = $this->source->getAction();
            $name    = $actions['as'];
        }

        $this->name = $name;
        return $this;
    }

    /**
     * Verifica se a rota é a rota atual no navegador
     *
     * @return boolean
     */
    public function isCurrent() : bool
    {
        $route = RouteFacade::current();

        return (! $route || $route->getName() != $this->name) ? false : true;
    }

    /**
     * Define a URL da rota
     *
     * @param string $url
     * @return Route
     */
    private function setUrl(string $url = null) : Route
    {  
        $this->url = $url ?? route($this->name, $this->params);
        return $this;
    }

    /**
     * Retorna o nome da rota 
     *
     * @return string
     */
    protected function getName() : string
    {
        return $this->name; 
    }

    /**
     * Retorna os parametros
     *
     * @return array
     */
    protected function getParams() : array
    {
        return $this->params;
    }
}