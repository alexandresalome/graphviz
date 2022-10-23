<?php
/*
 * This file is part of PHP Graphviz.
 * (c) Alexandre Salomé <graphviz@pub.salome.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Graphviz\Tests;

use Graphviz\AttributeBag;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Graphviz\AttributeBag
 */
class AttributeBagTest extends TestCase
{
    public function testGet(): void
    {
        $bag = new AttributeBag([
            'foo' => 'bar',
            'bar' => 'baz',
        ]);

        $this->assertSame('bar', $bag->get('foo'));
        $this->assertNull($bag->get('baz'));
        $this->assertSame('foo', $bag->get('baz', 'foo'));
    }

    public function testHas(): void
    {
        $bag = new AttributeBag([
            'foo' => 'bar',
            'bar' => 'baz',
        ]);

        $this->assertTrue($bag->has('foo'));
        $this->assertFalse($bag->has('baz'));
    }

    public function testSet(): void
    {
        $bag = new AttributeBag([
            'foo' => 'bar',
            'bar' => 'baz',
        ]);

        $this->assertNull($bag->get('name'));
        $bag->set('name', 'alice');
        $this->assertSame('alice', $bag->get('name'));
    }

    public function testCount(): void
    {
        $bag = new AttributeBag(['foo' => 'bar', 'bar' => 'baz']);
        $this->assertCount(2, $bag);
        $bag->set('baz', 'foo');
        $this->assertCount(3, $bag);
    }

    public function testAll(): void
    {
        $all = ['foo' => 'bar', 'bar' => 'baz'];
        $bag = new AttributeBag($all);
        $this->assertSame($all, $bag->all());
    }
}
