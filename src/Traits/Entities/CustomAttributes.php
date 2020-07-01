<?php

namespace Maestriam\Hiker\Traits\Entities;

/**
 * 
 */
trait CustomAttributes
{
    private $attributes = [];

    /**
     * Undocumented function
     *
     * @param array $attrs
     * @return void
     */
    private function loadAttrs(array $attrs) 
    {
        dump('carregando');
        dump($attrs);
        dump($this->name);
        return $this->attributes = $attrs;
    }

    /**
     * Undocumented function
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    private function setCustomAttr(string $key ,$value) 
    {
        $this->attributes[$key] = $value;       
        
        return $value;
    }

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
            return $this->getCustomAttr($key);
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
    private final function getCustomAttr(string $key)
    {
        return $this->attributes[$key];        
    }
}
