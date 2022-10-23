<?php

namespace Graphviz\Tests;

use Graphviz\Digraph;
use PHPUnit\Framework\TestCase;

class DigraphTest extends TestCase
{
    public function testIsDirected(): void
    {
        $graph = new Digraph();
        $this->assertTrue($graph->isDirected());
    }
}
