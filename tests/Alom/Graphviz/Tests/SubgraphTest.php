<?php
/*
 * This file is part of Alom Graphviz.
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alom\Graphviz\Tests;

use Alom\Graphviz\Digraph;
use Alom\Graphviz\DirectedEdge;
use Alom\Graphviz\Node;
use Alom\Graphviz\Subgraph;

class SubgraphTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException LogicException
     */
    public function testUnknownParent()
    {
        $subgraph = new Subgraph('S');
        $subgraph->edge(array('A', 'B', 'C'));

        $subgraph->render();

        $this->markTestIncomplete('This test has not been fully implemented yet.');
    }

    public function testNestedSubGraphs()
    {
        $graph = new Digraph('G');
        $graph->node('A')->edge(array('A', 'B'));
        $sub1 = $graph->subgraph('cluster_1')->node('B')->edge(array('B', 'C'));
        $sub2 = $sub1->subgraph('cluster_2')->node('C')->edge(array('C', 'D'));
        $sub3 = $sub2->subgraph('cluster_3')->node('D')->edge(array('D', 'E'));

        // Assert hierarchy is correct
        $this->assertEquals('cluster_2', $sub3->end()->getId());
        $this->assertEquals('cluster_1', $sub3->end()->end()->getId());
        $this->assertTrue($sub3->end()->end()->end() instanceof Digraph);

        // Inspect the instructions for each graph.
        $instructions = $sub3->getInstructions();
        $this->assertEquals(2, count($instructions));
        $this->assertTrue($instructions[0] instanceof Node);
        $this->assertTrue($instructions[1] instanceof DirectedEdge);
        $this->assertEquals(array('D', 'E'), $instructions[1]->getList());

        $instructions = $sub2->getInstructions();
        $this->assertEquals(3, count($instructions));
        $this->assertTrue($instructions[0] instanceof Node);
        $this->assertTrue($instructions[1] instanceof DirectedEdge);
        $this->assertTrue($instructions[2] instanceof Subgraph);
        $this->assertEquals(array('C', 'D'), $instructions[1]->getList());

        $instructions = $sub1->getInstructions();
        $this->assertEquals(3, count($instructions));
        $this->assertTrue($instructions[0] instanceof Node);
        $this->assertTrue($instructions[1] instanceof DirectedEdge);
        $this->assertTrue($instructions[2] instanceof Subgraph);
        $this->assertEquals(array('B', 'C'), $instructions[1]->getList());

        $instructions = $graph->getInstructions();
        $this->assertEquals(3, count($instructions));
        $this->assertTrue($instructions[0] instanceof Node);
        $this->assertTrue($instructions[1] instanceof DirectedEdge);
        $this->assertTrue($instructions[2] instanceof Subgraph);
        $this->assertEquals(array('A', 'B'), $instructions[1]->getList());
    }
}
