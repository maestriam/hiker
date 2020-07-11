<?php

namespace Maestriam\Hiker\Traits\Foundation;

use Maestriam\Hiker\Foundation\CustomAttribute;

trait CustomizesAttributes
{   
    /**
     * Atributos personalizados
     *
     * @var CustomAttribute
     */
    private static $customInstance = [];

    /**
     * Retorna o singleton das  RNs de atributos customizados
     *
     * @return CustomAttribute
     */
    public final function custom() : CustomAttribute
    {
        if (! isset(self::$customInstance[$this->name])) {
            self::$customInstance[$this->name] = new CustomAttribute();
        }

        return self::$customInstance[$this->name];
    }

    /**
     * Retorna o valor de um atributo para o cliente,
     * seja ele do próprio objeto ou criado de forma dinâmica
     *
     * @param string $key
     * @return mixed
     */
    public final function getCustomAttribute(string $key)
    {                
        if ($key == 'attributes') {
            return $this->custom()->all();
        }

        if ($this->hasFunction($key)) {
            return $this->execFunction($key);
        }

        if ($this->custom()->has($key)) {
            return $this->custom()->get($key);
        }

        return null;
    }

    /**
     * Retorna se existe uma função com um determinado nome de chave
     *
     * @param string $key
     * @return boolean
     */
    private final function hasFunction(string $key) : bool
    {
        $function = $this->getFunctionName($key);

        return method_exists($this, $function);
    }

    /**
     * Executa uma função com um determinado nome de chave
     *
     * @param string $key
     * @return mixed
     */
    private final function execFunction(string $key)
    {
        $function = $this->getFunctionName($key);

        return $this->$function();
    }

    /**
     * Gera um nome de método de acordo com a chave
     *
     * @param string $key
     * @return string
     */
    private final function getFunctionName(string $key) : string
    {
        return 'get' . ucfirst($key);
    }
}


