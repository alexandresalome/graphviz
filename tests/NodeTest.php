<?php
/*
 * This file is part of PHP Graphviz.
 * (c) Alexandre Salomé <graphviz@pub.salome.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Graphviz\Tests;

use Graphviz\Graph;
use Graphviz\Node;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Graphviz\Node
 */
class NodeTest extends TestCase
{
    public function testGetId(): void
    {
        $node = new Node(new Graph(), 'A');
        $this->assertSame('A', $node->getId());
    }

    public function testGetAttributeBag(): void
    {
        $node = new Node(new Graph(), 'A', ['foo' => 'bar']);
        $this->assertSame('bar', $node->getAttributeBag()->get('foo'));
    }

    public function testAttributes(): void
    {
        $node = new Node(new Graph(), 'A', ['foo' => 'bar']);

        $this->assertSame('bar', $node->getAttribute('foo'));
        $this->assertSame('foo', $node->getAttribute('not-existing', 'foo'));

        $node->attribute('bar', 'baz');

        $this->assertSame('baz', $node->getAttribute('bar'));
    }

    public function testEnd(): void
    {
        $graph = new Graph();
        $node = new Node($graph, 'A');
        $this->assertSame($node->end(), $graph);
    }
}
