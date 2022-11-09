<?php
/*
 * This file is part of PHP Graphviz.
 * (c) Alexandre Salomé <graphviz@pub.salome.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Graphviz;

use Graphviz\Output\DotRenderer;

/**
 * Base graph instruction.
 *
 * @author Alexandre Salomé <graphviz@pub.salome.fr>
 */
abstract class AbstractGraph implements InstructionInterface
{
    /**
     * Graph identifier.
     */
    protected string $id;

    /**
     * Parent graph.
     */
    protected ?AbstractGraph $parent;

    /** @var array<InstructionInterface> Instructions list */
    protected array $instructions = [];

    /**
     * Creates a new graph.
     *
     * @param string        $id     Identifier of the graph
     * @param AbstractGraph $parent Parent element
     */
    public function __construct(string $id = 'G', ?AbstractGraph $parent = null)
    {
        $this->id = $id;
        $this->parent = $parent;
    }

    abstract public function isDirected(): bool;

    /**
     * Returns the identifier of graph.
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Returns the list of the instructions.
     *
     * @return array<InstructionInterface>
     */
    public function getInstructions(): array
    {
        return $this->instructions;
    }

    /**
     * Returns a node or a subgraph, given his id.
     *
     * @param string $id the identifier of the node/graph to fetch
     *
     * @throws \InvalidArgumentException node or graph not found
     */
    public function get(string $id): Node|AbstractGraph
    {
        foreach ($this->instructions as $instruction) {
            if (!$instruction instanceof Node && !$instruction instanceof self) {
                continue;
            }

            if ($instruction->getId() === $id) {
                return $instruction;
            }
        }

        throw new \InvalidArgumentException(sprintf('Found no node or graph with id "%s" in "%s".', $id, $this->id));
    }

    /**
     * @param array<string|string[]> $path
     *
     * @throws \InvalidArgumentException Edge not found
     */
    public function getEdge(array $path): Edge
    {
        foreach ($this->instructions as $instruction) {
            if (!$instruction instanceof Edge) {
                continue;
            }

            if ($instruction->getPath() === $path) {
                return $instruction;
            }
        }

        $pathAsText = (new DotRenderer())->renderPath($path, false);

        throw new \InvalidArgumentException(sprintf('Found no edge with path "%s" in "%s".', $pathAsText, $this->id));
    }

    /**
     * Adds an assignment instruction.
     *
     * @param string         $name  Name of the value to assign
     * @param string|RawText $value Value to assign
     *
     * @return self Fluid-interface
     *
     * @throws \InvalidArgumentException Invalid method usage (used for graph/node/edge)
     */
    public function set(string $name, string|RawText $value): self
    {
        if (in_array($name, ['graph', 'node', 'edge'])) {
            throw new \InvalidArgumentException(sprintf('Use method "attr" for setting "%s".', $name));
        }

        $this->instructions[] = new Assign($name, $value);

        return $this;
    }

    /**
     * Define attributes for node/edge/graph.
     *
     * @param string                                     $name       Name of type
     * @param array<string, string|RawText>|AttributeBag $attributes Attributes of the type
     */
    public function attr(string $name, array|AttributeBag $attributes): self
    {
        $this->instructions[] = new AttributeSet($name, $attributes);

        return $this;
    }

    /**
     * Starts a subgraph.
     *
     * @param string $id Identifier of subgraph
     */
    public function subgraph(string $id): Subgraph
    {
        return $this->instructions[] = new Subgraph($this, $id);
    }

    /**
     * Created a new node on graph.
     *
     * @param string                                     $id         Identifier of node
     * @param array<string, string|RawText>|AttributeBag $attributes Attributes to set on node
     *
     * @return self Fluid-interface
     */
    public function node(string $id, array|AttributeBag $attributes = []): self
    {
        $this->instructions[] = new Node($this, $id, $attributes);

        return $this;
    }

    /**
     * Created multiple nodes on the graph in one command.
     *
     * @param array<string|int, string|array<string, string|RawText>> $nodes an array containing either a string (node identifier) or a key associated to an array of attributes
     *
     * @return self Fluid-interface
     */
    public function nodes(array $nodes): self
    {
        foreach ($nodes as $key => $value) {
            if (is_int($key) && is_string($value)) {
                $this->node($value);
            } elseif (is_string($key) && is_array($value)) {
                $this->node($key, $value);
            } else {
                throw new \InvalidArgumentException(sprintf('Expected either (int => string) or (string => array), got (%s => %s).', strtolower(gettype($key)), strtolower(gettype($value))));
            }
        }

        return $this;
    }

    /**
     * Created a new node on graph.
     *
     * @param string                                     $id         Identifier of node
     * @param array<string, string|RawText>|AttributeBag $attributes Attributes to set on node
     */
    public function beginNode(string $id, array|AttributeBag $attributes = []): Node
    {
        return $this->instructions[] = new Node($this, $id, $attributes);
    }

    /**
     * Created a new edge on graph.
     *
     * @param array<string|array<string>>                $list       List of edges
     * @param array<string, string|RawText>|AttributeBag $attributes Attributes to set on edge
     *
     * @return self Fluid-interface
     */
    public function edge(array $list, array|AttributeBag $attributes = []): self
    {
        $this->beginEdge($list, $attributes);

        return $this;
    }

    /**
     * Creates a new edge on graph.
     *
     * @param array<string|array<string>>               $list       List of edges
     * @param array<string,string|RawText>|AttributeBag $attributes Attributes to set on edge
     */
    public function beginEdge(array $list, array|AttributeBag $attributes = []): Edge
    {
        return $this->instructions[] = new Edge($this, $list, $attributes);
    }

    /**
     * Adds a new comment line to the graph (starting with //, or #).
     *
     * If you pass a multiline string to this method, it will append multiple
     * comment lines.
     *
     * @param string $comment   The comment to add
     * @param bool   $withSpace Adds a space at the beginning of the comment
     * @param bool   $cppStyle  Indicates if it's a classic (//) or C++ style (#)
     *
     * @return $this Fluid interface
     */
    public function commentLine(string $comment, bool $withSpace = true, bool $cppStyle = false): self
    {
        $space = $withSpace ? ' ' : '';
        $prefix = $cppStyle ? '#' : '//';
        foreach (explode("\n", $comment) as $line) {
            $this->instructions[] = new Comment($prefix.$space.$line);
        }

        return $this;
    }

    /**
     * Adds a new comment block to the graph (starting with /*).
     *
     * If you pass a multiline string to this method, it will append multiple
     * comment lines.
     *
     * @param string $comment   The comment to add
     * @param bool   $withSpace Adds a space at the beginning of the comment
     *
     * @return $this Fluid interface
     */
    public function commentBlock(string $comment, bool $withSpace = true): self
    {
        $lines = explode("\n", $comment);
        if ($withSpace) {
            $lines = array_merge(
                ['/*'],
                array_map(fn ($line) => ' * '.$line, $lines),
                [' */'],
            );
        } else {
            $lines[0] = '/*'.$lines[0];
            $last = count($lines) - 1;
            $lines[$last] .= '*/';
        }

        $this->instructions[] = new Comment(implode("\n", $lines), $withSpace);

        return $this;
    }

    /**
     * Fluid-interface to access parent.
     */
    public function end(): ?AbstractGraph
    {
        return $this->parent;
    }

    public function render(): string
    {
        return (new DotRenderer())->render($this);
    }
}
