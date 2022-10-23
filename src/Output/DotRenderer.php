<?php
/*
 * This file is part of PHP Graphviz.
 * (c) Alexandre Salomé <graphviz@pub.salome.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Graphviz\Output;

use Graphviz\AbstractGraph;
use Graphviz\Assign;
use Graphviz\AttributeBag;
use Graphviz\AttributeSet;
use Graphviz\Digraph;
use Graphviz\Edge;
use Graphviz\Graph;
use Graphviz\InstructionInterface;
use Graphviz\Node;
use Graphviz\RawText;
use Graphviz\Subgraph;

class DotRenderer implements RendererInterface
{
    private DotEscaper $escaper;

    public function __construct(
        private string $indentSpacer = '    ',
    ) {
        $this->escaper = new DotEscaper();
    }

    public function render(AbstractGraph $graph, int $indent = 0): string
    {
        $margin = str_repeat($this->indentSpacer, $indent);
        if ($graph instanceof Graph) {
            $graphKeyword = 'graph';
        } elseif ($graph instanceof Digraph) {
            $graphKeyword = 'digraph';
        } elseif ($graph instanceof Subgraph) {
            $graphKeyword = 'subgraph';
        } else {
            throw new \LogicException(sprintf('Unknown graph class of type "%s".', get_class($graph)));
        }
        $header = $graphKeyword.' '.$graph->getId();

        $result = $margin.$header." {\n";
        foreach ($graph->getInstructions() as $instruction) {
            $result .= $this->renderInstruction($instruction, $indent + 1);
        }
        $result .= $margin."}\n";

        return $result;
    }

    /**
     * Render a list of path identifiers.
     *
     * @param array<string|string[]> $path
     */
    public function renderPath(array $path, bool $directed): string
    {
        $segments = [];
        foreach ($path as $id) {
            if (is_array($id)) {
                $segments[] = (new DotEscaper())->escapePath($id);
            } else {
                $segments[] = (new DotEscaper())->escape($id);
            }
        }

        $operator = $directed ? ' -> ' : ' -- ';

        return implode($operator, $segments);
    }

    public function renderAssign(Assign $assign, int $indent = 0): string
    {
        return
            str_repeat($this->indentSpacer, $indent).
            $this->renderInlineAssignment(
                $assign->getName(),
                $assign->getValue(),
            ).
            ";\n"
        ;
    }

    /**
     * Renders an inline assignment (without indent or end return line).
     *
     * It will handle escaping, according to the value.
     *
     * @param string         $name  A name
     * @param string|RawText $value A value
     */
    public function renderInlineAssignment(string $name, string|RawText $value, int $indent = 0): string
    {
        if ($value instanceof RawText) {
            $value = $value->getText();
        } else {
            $value = $this->escaper->escape($value);
        }

        return
            str_repeat($this->indentSpacer, $indent).
            $this->escaper->escape($name).
            '='.
            $value
        ;
    }

    public function renderEdge(Edge $edge, int $indent = 0): string
    {
        $path = $this->renderPath($edge->getPath(), $edge->isDirected());
        $attributes = $this->renderAttributeBag($edge->getAttributeBag());

        return str_repeat($this->indentSpacer, $indent).
            $path.
            ('' !== $attributes ? ' '.$attributes : $attributes).";\n"
        ;
    }

    public function renderAttributeBag(AttributeBag $attributes): string
    {
        if (0 === count($attributes)) {
            return '';
        }

        $exp = [];
        foreach ($attributes->all() as $name => $value) {
            $exp[] = (new DotRenderer())->renderInlineAssignment($name, $value);
        }

        return '['.implode(', ', $exp).']';
    }

    public function renderAttributeSet(AttributeSet $attributeSet, int $indent = 0): string
    {
        return
            str_repeat($this->indentSpacer, $indent).
            $attributeSet->getName().
            ' '.
            $this->renderAttributeBag($attributeSet->getAttributeBag()).
            ";\n"
        ;
    }

    public function renderNode(Node $node, int $indent = 0): string
    {
        $attributes = $this->renderAttributeBag($node->getAttributeBag());

        return
            str_repeat($this->indentSpacer, $indent).
            (new DotEscaper())->escape($node->getId()).
            ('' === $attributes ? '' : ' '.$attributes).
            ";\n"
        ;
    }

    public function renderInstruction(InstructionInterface $instruction, int $indent = 0): string
    {
        if ($instruction instanceof Assign) {
            return $this->renderAssign($instruction, $indent);
        }

        if ($instruction instanceof AttributeSet) {
            return $this->renderAttributeSet($instruction, $indent);
        }

        if ($instruction instanceof Node) {
            return $this->renderNode($instruction, $indent);
        }

        if ($instruction instanceof Edge) {
            return $this->renderEdge($instruction, $indent);
        }

        if ($instruction instanceof AbstractGraph) {
            return $this->render($instruction, $indent);
        }

        throw new \InvalidArgumentException(sprintf('Unexpected instruction of type "%s" while rendering.', get_class($instruction)));
    }
}
