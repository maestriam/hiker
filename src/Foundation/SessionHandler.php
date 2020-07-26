<?php

namespace Maestriam\Hiker\Foundation;

use Illuminate\Support\Facades\Session;

class SessionHandler
{   
    /**
     * Prefixo de chave de sessão
     *
     * @var string
     */
    private $prefix = 'hiker-session';

    /**
     * 
     *
     * @var string
     */
    private $tag = null;
    
    /**
     * 
     *
     * @param string $tag
     * @return SessionHandler
     */
    public function tag(string $tag) : SessionHandler
    {
        $this->tag = $tag;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $key
     * @param mixed $value
     * @return boolean
     */
    public function put(string $key, $value) 
    {
        $key = $this->key($key);
        
        Session::put($key, $value);
    }
    
    /**
     * Retorna um valor salvo na sessão de acordo com o chave 
     *
     * @param string $key
     * @return void
     */
    public function get(string $key)
    {
        $key = $this->key($key);
    
        return Session::get($key);
    }

    /**
     * Retorna a chave para adicionar/resgatar dados da sessão
     *
     * @param string $key
     * @return string
     */
    private function key(string $key) : string
    {
        $pre = ($this->tag) ? $this->prefix . '.' . $this->tag : $this->prefix;
        
        return $pre . '.' . $key;
    }
}