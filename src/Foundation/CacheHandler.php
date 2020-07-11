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
     * Undocumented variable
     *
     * @var string
     */
    private $notepad = 'hiker-notes';

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
        $this->subject = $subject . '-';
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

        $stored = Cache::put($key, $value); 

        if ($stored) {
            return $this->note($key);
        }

        return false;
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
     * Destroi os valores de cache de acordo com a chave
     *
     * @param string $key
     * @return bool
     */
    public function destroy(string $key) : bool
    {
        $key = $this->key($key);

        $destroyed = Cache::forget($key);

        if ($destroyed) {
            return $this->erase($key);
        }

        return false;
    }

    /**
     * Apaga TODAS as chaves salvas pelo Hiker
     *
     * @return void
     */
    public function purge()
    {
        $notes = $this->notes();

        foreach($notes as $key) {
            Cache::forget($key);
        }

        return Cache::forget($this->notepad);
    }

    /**
     * Anota as chaves que já foram utilizadas e salva no cache
     *
     * @param string $key
     * @return boolean
     */
    private function note(string $key) : bool
    {
        $notes = $this->notes();
        
        array_push($notes, $key);

        return Cache::put($this->notepad, $notes);
    }
    
    /**
     * Retorna todas as anotações das chaves salvas no Cache
     *
     * @return array
     */
    private function notes() : array
    {
        $notes = Cache::get($this->notepad) ?? [];
        
        return $notes;
    }

    /**
     * Apaga a chave nas anotações do cache
     *
     * @param string $key
     * @return boolean
     */
    private function erase(string $key) : bool
    {
        $notes = $this->notes();

        $index = array_search($key, $notes);

        unset($notes[$index]);

        return Cache::put($this->notepad, $notes);
    }
    
    /**
     * Atualiza os dados de cache de acordo com a chave
     *
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function update(string $key, $value)
    {    
        $this->destroy($key);

        return $this->store($key, $value);
    }

    /**
     * Retorna o nome completo da chave 
     *
     * @param string $key
     * @return string
     */
    private function key(string $key) : string
    {
        return $this->prefix . $this->subject . $key;
    }
}