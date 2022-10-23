<?php

namespace Graphviz\Tests\Output;

use Graphviz\AbstractGraph;
use Graphviz\Assign;
use Graphviz\AttributeBag;
use Graphviz\AttributeSet;
use Graphviz\Digraph;
use Graphviz\Edge;
use Graphviz\Graph;
use Graphviz\InstructionInterface;
use Graphviz\Node;
use Graphviz\Output\DotRenderer;
use Graphviz\RawText;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Graphviz\Output\DotRenderer
 */
class DotRendererTest extends TestCase
{
    private DotRenderer $renderer;

    protected function setUp(): void
    {
        $this->renderer = new DotRenderer();
    }

    public function testRenderGraph(): void
    {
        $graph = new Graph();
        $graph
            ->set('rankdir', 'LR')
            ->attr('node', ['shape' => 'record'])
            ->node('A')
            ->subgraph('sub')
            ->node('B')
            ->end()
            ->edge(['A', 'B'])
        ;

        $expected = <<<'EXPECTED'
        graph G {
            rankdir=LR;
            node [shape=record];
            A;
            subgraph sub {
                B;
            }
            A -- B;
        }

        EXPECTED;

        $this->assertSame($expected, $this->renderer->render($graph));
    }

    public function testRenderDigraph(): void
    {
        $graph = new Digraph();
        $graph->node('A');

        $expected = <<<'EXPECTED'
        digraph G {
            A;
        }

        EXPECTED;

        $this->assertSame($expected, $this->renderer->render($graph));
    }

    public function testRenderInvalid(): void
    {
        $mock = $this->createMock(AbstractGraph::class);
        $this->expectException(\LogicException::class);
        $this->renderer->render($mock);
    }

    public function testRenderInvalidInstruction(): void
    {
        $invalid = $this->createMock(InstructionInterface::class);
        $this->expectException(\InvalidArgumentException::class);
        $this->renderer->renderInstruction($invalid);
    }

    public function testRenderAssign(): void
    {
        $assign = new Assign('foo', 'bar');
        $this->assertSame("foo=bar;\n", $this->renderer->renderAssign($assign));
    }

    public function testRenderAssignRawText(): void
    {
        $assign = new Assign('foo', new RawText('<>'));
        $this->assertSame("foo=<>;\n", $this->renderer->renderAssign($assign));
    }

    public function testRenderAttributeBagEmpty(): void
    {
        $bag = new AttributeBag();
        $this->assertSame('', $this->renderer->renderAttributeBag($bag));
    }

    /**
     * @dataProvider provideRenderPath
     *
     * @param array<string|array<string>> $path
     */
    public function testRenderPath(array $path, bool $directed, string $expectedResult): void
    {
        $this->assertSame($expectedResult, $this->renderer->renderPath($path, $directed));
    }

    /**
     * @return array<array{
     *    array<string|array<string>>,
     *    bool,
     *    string,
     * }>
     */
    public function provideRenderPath(): array
    {
        return [
            [['A', 'B'], true, 'A -> B'],
            [['A', 'B', 'C'], false, 'A -- B -- C'],
            [['A', ['B', 'C']], false, 'A -- B:C'],
            [['A', ['B', 'foo bar']], false, 'A -- B:"foo bar"'],
        ];
    }

    public function testRenderAttributeBagSingleNoEscaping(): void
    {
        $bag = new AttributeBag(['foo' => 'bar']);
        $this->assertSame('[foo=bar]', $this->renderer->renderAttributeBag($bag));
    }

    public function testRenderAttributeBagSingleEscaping(): void
    {
        $bag = new AttributeBag(['foo' => 'foo bar']);
        $this->assertSame('[foo="foo bar"]', $this->renderer->renderAttributeBag($bag));
    }

    public function testRenderAttributeBagMultiple(): void
    {
        $bag = new AttributeBag([
            'foo' => 'bar',
            'bar' => 'foo bar',
            'baz' => '',
        ]);
        $bag->set('bar', 'foo bar');
        $this->assertSame('[foo=bar, bar="foo bar", baz=""]', $this->renderer->renderAttributeBag($bag));
    }

    public function testRenderAttributeSet(): void
    {
        $set = new AttributeSet('node', ['foo' => 'bar']);
        $this->assertSame("node [foo=bar];\n", $this->renderer->renderAttributeSet($set));
    }

    public function testRenderEdge(): void
    {
        $edge = new Edge(new Digraph(), ['A', 'B']);
        $this->assertSame("A -> B;\n", $this->renderer->renderEdge($edge));
    }

    public function testRenderNode(): void
    {
        $node = new Node(new Graph(), 'A');
        $this->assertSame("A;\n", $this->renderer->renderNode($node));
    }
}
