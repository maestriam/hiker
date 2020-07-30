<?php

namespace Maestriam\Hiker\Foundation;

class UriParser 
{
    /**
     * Interpreta as informações vindas da URL e extrai
     * os parâmetros nvecessários
     *
     * @param string $uri
     * @return array
     */
    public function parse(string $uri) : array
    {
        $pieces = explode('/', $uri);

        if (empty($pieces)) {
            return [];
        }

        return $this->sanitize($pieces);
    }

    /**
     * Percorre todos os segmentos da URI e retorna
     * somente as passagens de parâmetro, sem caracteres indesejados
     *
     * @param array $pieces
     * @return void
     */
    private function sanitize(array $pieces) : array
    {
        $clean = [];

        foreach($pieces as $segment)
        {
            if (! $this->isParam($segment)) {
                continue;
            }

            $clean[] = $this->clean($segment);
        }

        return $clean;
    }

    /**
     * Verifica se é uma passagem de parâmetro na URL
     *
     * @param string $segment
     * @return boolean
     */
    private function isParam(string $segment) : bool
    {
        return (preg_match('/{/', $segment));
    }

    /**
     * Remove todos os caracteres da string
     *
     * @param string $dirty
     * @return string
     */
    private function clean(string $dirty) : string
    {
        $clean = str_replace('{', '', $dirty);
        $clean = str_replace('}', '', $clean);
        $clean = str_replace('?', '', $clean);

        return $clean;
    }
}
