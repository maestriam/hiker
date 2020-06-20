<?php

namespace Maestriam\Hiker\Traits\Entities;

/**
 * 
 */
trait MagicMethods
{
    private $attributes = [];

    /**
     * Retorna o valor de um atributo para o cliente,
     * seja ele do próprio objeto ou criado de forma dinâmica
     *
     * @param string $key
     * @return mixed
     */
    private final function getAttribute(string $key)
    {
        $func = 'get' . ucfirst($key);

        if (method_exists($this, $func)) {
            return $this->$func();
        }

        if ($this->hasAttribute($key)) {
            return $this->getCustomAttribute($key);
        }

        return null;
    }

    /**
     * Verifica se um há atributo definido pelo usuário 
     *
     * @param string $key
     * @return boolean
     */
    private final function hasAttribute(string $key) : bool
    {
        return (isset($this->attributes[$key]));
    }

    /**
     * Retorna o valor de um atributo dinâmico
     *
     * @param string $key
     * @return void
     */
    private final function getCustomAttribute(string $key)
    {
        return $this->attributes[$key];        
    }
}
