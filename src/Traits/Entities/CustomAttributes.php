<?php

namespace Maestriam\Hiker\Traits\Entities;

/**
 * 
 */
trait CustomAttributes
{
    /**
     * Retorna o valor de um atributo para o cliente,
     * seja ele do próprio objeto ou criado de forma dinâmica
     *
     * @param string $key
     * @return mixed
     */
    private final function getCustomAttribute(string $key)
    {                
        if ($key == 'attributes') {
            return $this->custom()->all();
        }

        $func = 'get' . ucfirst($key);

        if (method_exists($this, $func)) {
            return $this->$func();
        }

        if ($this->custom()->has($key)) {
            return $this->custom()->get($key);
        }

        return null;
    }
}


