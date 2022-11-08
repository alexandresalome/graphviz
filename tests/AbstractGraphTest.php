<?php
/*
 * This file is part of PHP Graphviz.
 * (c) Alexandre Salomé <graphviz@pub.salome.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Graphviz\Tests;

use Graphviz\AbstractGraph;
use Graphviz\Assign;
use Graphviz\AttributeSet;
use Graphviz\Edge;
use Graphviz\Node;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Graphviz\AbstractGraph
 */
class AbstractGraphTest extends TestCase
{
    public function testGet(): void
    {
        $graph = new TestGraph();
        $graph->subgraph('foo')
            ->node('bar', ['label' => 'baz'])
        ;

        $this->assertSame('baz', $graph->get('foo')->get('bar')->getAttribute('label'));
    }

    public function testGetNotExisting(): void
    {
        $graph = new TestGraph();
        $graph->set('rankdir', 'LR');
        $graph->node('foo');

        $this->expectException(\InvalidArgumentException::class);
        $graph->get('bar');
    }

    public function testGetEdge(): void
    {
        $graph = new TestGraph();
        $graph->set('rankdir', 'LR');
        $graph->edge(['A', 'B']);
        $graph->edge(['B', ['C', '1']]);

        $edge = $graph->getEdge(['A', 'B']);
        $this->assertSame(['A', 'B'], $edge->getPath());

        $edge = $graph->getEdge(['B', ['C', '1']]);
        $this->assertSame(['B', ['C', '1']], $edge->getPath());
    }

    public function testGetEdgeNotExisting(): void
    {
        $graph = new TestGraph();
        $graph->edge(['A', 'B']);
        $graph->edge(['B', ['C', '1']]);

        try {
            $graph->getEdge(['A', 'C']);
            $this->fail();
        } catch (\InvalidArgumentException $e) {
            $this->assertSame('Found no edge with path "A -- C" in "G".', $e->getMessage());
        }

        try {
            $graph->getEdge(['A', ['C', '2']]);
            $this->fail();
        } catch (\InvalidArgumentException $e) {
            $this->assertSame('Found no edge with path "A -- C:2" in "G".', $e->getMessage());
        }
    }

    public function testFluidInterfaceShort(): void
    {
        $graph = new TestGraph();

        $graph
            ->set('rankdir', 'LR')
            ->node('A')
            ->node('B')
            ->edge(['A', 'B'])
        ;

        $this->assertCount(4, $instructions = $graph->getInstructions());

        /** @var Assign $assign */
        $assign = $instructions[0];
        $this->assertInstanceOf(Assign::class, $assign);
        $this->assertSame('rankdir', $assign->getName());
        $this->assertSame('LR', $assign->getValue());

        /** @var Node $node */
        $node = $instructions[1];
        $this->assertInstanceOf(Node::class, $node);
        $this->assertSame('A', $node->getId());

        /** @var Node $node */
        $node = $instructions[2];
        $this->assertInstanceOf(Node::class, $node);
        $this->assertSame('B', $node->getId());

        /** @var Edge $edge */
        $edge = $instructions[3];
        $this->assertInstanceOf(Edge::class, $edge);
        $this->assertSame(['A', 'B'], $edge->getPath());
    }

    public function testNode(): void
    {
        $graph = new TestGraph();
        $return = $graph->node('A');
        $graph->node('B', ['foo' => 'bar']);

        $this->assertSame($graph, $return);
        $nodeA = $graph->get('A');
        $nodeB = $graph->get('B');
        $this->assertInstanceOf(Node::class, $nodeA);
        $this->assertInstanceOf(Node::class, $nodeB);
        $this->assertSame([$nodeA, $nodeB], $graph->getInstructions());
        $this->assertSame([], $nodeA->getAttributeBag()->all());
        $this->assertSame(['foo' => 'bar'], $nodeB->getAttributeBag()->all());
    }

    public function testNodes(): void
    {
        $graph = new TestGraph();
        $return = $graph->nodes([
           'A',
           'B' => ['foo' => 'bar'],
        ]);

        $this->assertSame($graph, $return);
        $nodeA = $graph->get('A');
        $nodeB = $graph->get('B');
        $this->assertInstanceOf(Node::class, $nodeA);
        $this->assertInstanceOf(Node::class, $nodeB);
        $this->assertSame([$nodeA, $nodeB], $graph->getInstructions());
        $this->assertSame([], $nodeA->getAttributeBag()->all());
        $this->assertSame(['foo' => 'bar'], $nodeB->getAttributeBag()->all());
    }

    /**
     * @dataProvider provideNodesInvalid
     */
    public function testNodesInvalid(mixed $key, mixed $value, string $errorMessage): void
    {
        $graph = new TestGraph();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectDeprecationMessage($errorMessage);
        $graph->nodes([$key => $value]);
    }

    /**
     * @return array<array{mixed, mixed, string}>
     */
    public function provideNodesInvalid(): array
    {
        return [
            [1, 1, 'Expected either (int => string) or (string => array), got (integer => integer).'],
            ['a', 'a', 'Expected either (int => string) or (string => array), got (string => string).'],
            ['a', new \DateTime(), 'Expected either (int => string) or (string => array), got (string => object).'],
        ];
    }

    public function testFluidInterfaceVerbose(): void
    {
        $graph = new TestGraph();

        $graph
            ->beginNode('A')
                ->attribute('color', 'red')
            ->end()
            ->beginEdge(['A', 'B'])
                ->attribute('color', 'blue')
            ->end()
        ;

        $this->assertCount(2, $instructions = $graph->getInstructions());

        /** @var Node $node */
        $node = $instructions[0];
        $this->assertInstanceOf(Node::class, $node);
        $this->assertSame('A', $node->getId());
        $this->assertSame('red', $node->getAttributeBag()->get('color'));

        /** @var Edge $edge */
        $edge = $instructions[1];
        $this->assertInstanceOf(Edge::class, $edge);
        $this->assertSame(['A', 'B'], $edge->getPath());
        $this->assertSame('blue', $edge->getAttributeBag()->get('color'));
    }

    public function testAttr(): void
    {
        $graph = new TestGraph();
        $graph->attr('node', ['color' => 'blue']);

        $this->assertCount(1, $instructions = $graph->getInstructions());
        /** @var AttributeSet $attributeSet */
        $attributeSet = $instructions[0];
        $this->assertInstanceOf(AttributeSet::class, $attributeSet);
        $this->assertSame('node', $attributeSet->getName());
        $this->assertSame('blue', $attributeSet->getAttributeBag()->get('color'));
    }

    /**
     * @dataProvider provideIncorrectSetUsage
     */
    public function testIncorrectSetUsage(string $name): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $graph = new TestGraph();
        $graph->set($name, 'foo');
    }

    /**
     * @return string[][]
     */
    public function provideIncorrectSetUsage(): array
    {
        return [
            ['graph'],
            ['edge'],
            ['node'],
        ];
    }

    public function testSubGraph(): void
    {
        $graph = new TestGraph();
        $subgraph = $graph->subgraph('foo');
        $subgraph->edge(['A', 'B']);

        $this->assertCount(1, $graph->getInstructions(), 'Count of instructions');
        $this->assertSame('foo', $subgraph->getId(), 'Subgraph identifier');
        $this->assertSame($graph, $subgraph->end(), 'Subgraph end');

        $this->assertSame("subgraph foo {\n    A -> B;\n}\n", $subgraph->render());
    }

    public function testCommentLine(): void
    {
        $graph = new TestGraph();
        $subgraph = $graph->subgraph('foo');
        $subgraph->commentLine('Foo');
        $this->assertSame("subgraph foo {\n    // Foo\n}\n", $subgraph->render());
    }

    public function testCommentLineMultiple(): void
    {
        $graph = new TestGraph();
        $subgraph = $graph->subgraph('foo');
        $subgraph->commentLine("Foo\nBar");
        $this->assertSame("subgraph foo {\n    // Foo\n    // Bar\n}\n", $subgraph->render());
    }

    public function testCommentLineNoSpace(): void
    {
        $graph = new TestGraph();
        $subgraph = $graph->subgraph('foo');
        $subgraph->commentLine('Foo', false);
        $this->assertSame("subgraph foo {\n    //Foo\n}\n", $subgraph->render());
    }

    public function testCommentLineCppStyle(): void
    {
        $graph = new TestGraph();
        $subgraph = $graph->subgraph('foo');
        $subgraph->commentLine('Foo', true, true);
        $this->assertSame("subgraph foo {\n    # Foo\n}\n", $subgraph->render());
    }
}

class TestGraph extends AbstractGraph
{
    public function isDirected(): bool
    {
        return true;
    }
}
