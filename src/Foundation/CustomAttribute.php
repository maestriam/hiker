<?php

namespace Maestriam\Hiker\Foundation;

class CustomAttribute
{
    /**
     * Lista de atributos
     *
     * @var array
     */
    private $attributes = [];

    /**
     * Define um atributo customizado
     *
     * @param string $key
     * @param string $value
     * @return string
     */
    public function set(string $key, string $value) : string
    {
        return $this->attributes[$key] = $value;
    }

    /**
     * Carrega uma lista de atributos para a classe
     *
     * @param array $attributes
     * @return void
     */
    public function load(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * Retorna TODOS os atributos customizados
     *
     * @return array
     */
    public function all() : array
    {
        return $this->attributes;
    }

    /**
     * Retorna o valor de um atributo dinâmico
     *
     * @param string $key
     * @return void
     */
    public function get(string $key) : ?string
    {
        if (! $this->has($key)) {
            return null;
        }

        return $this->attributes[$key];
    }
    
    /**
     * Verifica se há um atributo definido pelo usuário 
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key) : bool
    {
        return (isset($this->attributes[$key]));
    }
}