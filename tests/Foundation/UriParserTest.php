<?php

namespace Maestriam\Hiker\Tests\Foundation;

use Tests\TestCase;
use Maestriam\Hiker\Traits\Foundation\ManagesUri;

class UriParserTest extends TestCase
{
    use ManagesUri;
    
    /**
     * Verifica se consegue interpretar uma URI simples,
     * sem parâmetros
     *
     * @return void
     */
    public function testSimpleUri()
    {
        $uri = '/test';
        $ret = $this->uri()->parse($uri);

        $this->assertIsArray($ret);
        $this->assertEmpty($ret);
    }

    /**
     * Verifica se consegue interpretar uma URI com parâmetros
     *
     * @return void
     */
    public function testWithParams()
    {
        $uri = '/test/{id}';
        $ret = $this->uri()->parse($uri);

        $this->assertIsArray($ret);
        $this->assertCount(1, $ret);
    }

    /**
     * Verifica se consegue interpretar uma URI com parâmetros opcionais
     *
     * @return void
     */
    public function testWithOptional()
    {
        $uri = '/test/{id}/{view?}';
        $ret = $this->uri()->parse($uri);

        $this->assertIsArray($ret);
        $this->assertCount(2, $ret);
    }

    /**
     * Verifica se consegue interpretar uma URI com parâmetros opcionais,
     * não-sequencial e com segmentos fixos
     * 
     * @return void
     */
    public function testRandomPosition()
    {
        $uri = '/test/{id}/segment/view/{view?}';
        $ret = $this->uri()->parse($uri);

        $this->assertIsArray($ret);
        $this->assertCount(2, $ret);
    }
}
