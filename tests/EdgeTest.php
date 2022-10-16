<?php
/*
 * This file is part of PHP Graphviz.
 * (c) Alexandre Salomé <graphviz@pub.salome.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Graphviz\Tests;

use Graphviz\Digraph;
use Graphviz\Edge;
use Graphviz\Graph;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Graphviz\Edge
 */
class EdgeTest extends TestCase
{
    public function testStructure(): void
    {
        $graph = new Graph();
        $edge = new Edge($graph, ['A', 'B']);

        $this->assertCount(2, $edge->getPath(), '2 elements in the edge');
        $edge->attribute('foo', 'bar');
        $this->assertSame('bar', $edge->getAttributeBag()->get('foo'), 'Value of attribute is correct');
        $this->assertSame($graph, $edge->end(), 'End returns parent');
    }

    public function testAttributes(): void
    {
        $edge = new Edge(new Graph(), ['A', 'B'], ['foo' => 'bar']);

        $this->assertSame('bar', $edge->getAttribute('foo'));
        $this->assertSame('foo', $edge->getAttribute('not-existing', 'foo'));

        $edge->attribute('bar', 'baz');

        $this->assertSame('baz', $edge->getAttribute('bar'));
    }

    public function testIsDirectedDigraph(): void
    {
        // directed graph
        $edge = new Edge(new Digraph(), ['A', 'B']);
        $this->assertTrue($edge->isDirected());
    }

    public function testIsDirectedGraph(): void
    {
        // directed graph
        $edge = new Edge(new Graph(), ['A', 'B']);
        $this->assertFalse($edge->isDirected());
    }
}
