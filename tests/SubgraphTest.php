<?php

namespace Graphviz\Tests;

use Graphviz\Digraph;
use Graphviz\Graph;
use Graphviz\Subgraph;
use PHPUnit\Framework\TestCase;

class SubgraphTest extends TestCase
{
    public function testIsDirectedGraph(): void
    {
        $graph = new Subgraph(new Graph());
        $this->assertFalse($graph->isDirected());
    }

    public function testIsDirectedDigraph(): void
    {
        $graph = new Subgraph(new Digraph());
        $this->assertTrue($graph->isDirected());
    }

    public function testIsDirectedSubgraph(): void
    {
        $graph = new Subgraph(new Subgraph(new Digraph()));
        $this->assertTrue($graph->isDirected());
    }
}
