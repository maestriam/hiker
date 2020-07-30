<?php

namespace Maestriam\Hiker\Foundation;

use Illuminate\Support\Facades\Session;
use Maestriam\Hiker\Exceptions\SessionNotInitializedException;

class SessionHandler
{   
    /**
     * Prefixo de chave de sessão
     *
     * @var string
     */
    private $prefix = 'hiker-session';

    /**
     * Adiciona uma categoria para
     *
     * @var string
     */
    private $tag = null;

    public function __construct()
    {
        $this->checkSession();
    }
    
    /**
     * Verifica se a sessão está iniciada
     * e funcionando corretamente
     *
     * @return void
     */
    private function checkSession()
    {
        Session::put('hiker-session', 'start');
        
        if (! Session::has('hiker-session')) {
            throw new SessionNotInitializedException();
        }

        Session::forget('hiker-session');
    }

    /**
     * Adiciona uma categoria para a sessão
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
     * Adiciona um valor para sessão de acordo com a chave
     *
     * @param string $key
     * @param mixed $value
     * @return boolean
     */
    public function put(string $key, string $value) 
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
    public function get(string $key) : ?string
    {
        $key = $this->key($key);
    
        return Session::get($key);
    }

    /**
     * Retorna a chave para adicionar/resgatar dados da sessão
     *
     * @param string $name
     * @return string
     */
    private function key(string $name) : string
    {
        $pre = ($this->tag) ? $this->prefix . '-' . $this->tag : $this->prefix;
        $key = $pre . '-' . $name;
        
        return $this->normalize($name);
    }

    /**
     * Retorna a chave para sessão sem caracteres indesejados
     *
     * @param string $key
     * @return string
     */
    private function normalize(string $key) : string
    {
        return str_replace('.', '', $key);
    }
}