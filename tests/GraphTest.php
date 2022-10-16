<?php

namespace Graphviz\Tests;

use Graphviz\Graph;
use PHPUnit\Framework\TestCase;

class GraphTest extends TestCase
{
    public function testIsDirected(): void
    {
        $graph = new Graph();
        $this->assertFalse($graph->isDirected());
    }
}
