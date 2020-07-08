<?php

namespace Maestriam\Hiker\Tests\Feature;

use Tests\TestCase;
use Maestriam\Hiker\Traits\Support\Hikeable;

class MenuAttributeTest extends TestCase
{
    use Hikeable;

    public function testAddAttribute()
    {
        $menu = $this->hiker()
                     ->menu('foo-menu-II')
                     ->attr('label', 'Foo');

        $this->assertIsString($menu->label);
        $this->assertEquals('Foo', $menu->label);

    }
}