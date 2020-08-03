<?php

namespace Maestriam\Hiker\Entities;

use Exception;
use Maestriam\Hiker\Contracts\Navigator;
use Maestriam\Hiker\Entities\Foundation;
use Illuminate\Routing\Route as RouteSource;
use Maestriam\Hiker\Traits\Foundation\ManagesUri;
use Illuminate\Support\Facades\Route as RouteFacade;
use Maestriam\Hiker\Exceptions\RouteHasNoNameException;

class Route extends Foundation implements Navigator
{
    use ManagesUri;

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


    private $uri = [];
    
    /**
     * Inicializa todos os atributos principais
     *
     * @param RouteSource $source
     */
    public function __construct(RouteSource $source = null, array $params = [])
    {
        $this->setSource($source)
             ->setName()
             ->parseUri()
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
        return $this->getCustomAttribute($key) ?? 
               $this->source->getAction($key);
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
        foreach ($this->uri as $key) {            
            
            $value = $params[$key] ?? $this->deduce($key);

            if (! $value) {
                continue;
            }

            $this->stack($key, $value)->note($key, $value);
        }

        return $this;
    }

    /**
     * Interpreta os valores passados na URI
     * para definir os parâmetros na rota
     *
     * @return void
     */
    public function parseUri() : Route
    {
        $uri = $this->source->uri;

        $this->uri = $this->uri()->parse($uri);
        
        return $this;
    }

    /**
     * Verifica se existe uma chave 
     *
     * @param string $key
     * @return boolean
     */
    private function paramExists(string $key) : bool
    {
        return (in_array($key, $this->uri));
    }
    
    /**
     * Tenta deduzir o valor para um parâmetro informado 
     *
     * @param string $key
     * @return string
     */
    private function deduce(string $key) : ?string
    {
        if ($this->isCurrent()) {
            return request($key);
        }

        return $this->last($key);  
    }

    /**
     * Adiciona um novo item para a pilha de parâmetros
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    private function stack(string $key, $value) : Route
    {
        $this->params[$key] = $value;
        return $this;
    }

    /**
     * Retorna o último o valor de um parâmetro visto
     *
     * @param string $key
     * @return string
     */
    private function last(string $key) : ?string
    {
        return $this->session()->tag($this->name)->get($key);
    }

    /**
     * Registra o valor do parâmetro utilizado na rota
     *
     * @param string $key
     * @return string|null
     */
    private function note(string $key, $value) 
    {
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
    private function setName() :  Route
    {
        $name = $this->source->getAction('as');

        if (! $name) {
            throw new RouteHasNoNameException($this->source->uri);
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
     * Define a URL da rota com os parâmetros processados
     * Caso não consiga, define a url como nulo
     *
     * @param string $url
     * @return Route
     */
    private function setUrl() : Route
    {  
        try {        
            $this->url = route($this->name, $this->params);
        } catch (Exception $e) {
            $this->url = null;
        } 

        return $this;
    }

    /**
     * Retorna o nome da rota 
     *
     * @return string
     */
    protected function getName() : ?string
    {
        return $this->name; 
    }

    /**
     * Retorna os parametros depois de processados
     *
     * @return array
     */
    protected function getParams() : array
    {
        return $this->params;
    }
}