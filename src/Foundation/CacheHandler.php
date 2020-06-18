<?php

namespace Maestriam\Hiker\Foundation;

use Illuminate\Support\Facades\Cache;

class CacheHandler
{
    /**
     * Prefixo da chave para o cache
     *
     * @var string
     */
    private $prefix  = 'hiker-';

    /**
     * Propósito do armazenamento
     *
     * @var string
     */
    private $subject = '';
    
    /**
     * Define qual será a finalidade do armazenamento,
     * se é para ser guardar menus ou breadcrumbs
     *
     * @param string $subject
     * @return CacheHandler
     */
    public function subject(string $subject) : CacheHandler
    {
        $this->subject = $subject;
        return $this;
    }
    
    /**
     * Armazena um novo valor no cache
     * definido pela um 
     *
     * @param string $key
     * @param mixed $value
     * @return boolean
     */
    public function store(string $key, $value) : bool
    {
        $key = $this->key($key);
        
        return Cache::put($key, $value); 
    }

    /**
     * Retorna o valor do cache
     *
     * @param string $key
     * @return mixed
     */
    public function get(string $key)
    {
        $key = $this->key($key);

        return Cache::get($key);
    }

    /**
     * Retorna o nome completo da chave 
     *
     * @param string $key
     * @return string
     */
    private function key(string $key) : string
    {
        return $this->prefix . '-' . $this->subject . '-' . $key;
    }
}