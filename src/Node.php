<?php
/*
 * This file is part of PHP Graphviz.
 * (c) Alexandre Salomé <graphviz@pub.salome.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Graphviz;

/**
 * Node instruction.
 *
 * @author Alexandre Salomé <graphviz@pub.salome.fr>
 */
class Node implements InstructionInterface
{
    /**
     * Parent instruction.
     */
    protected AbstractGraph $parent;

    /**
     * Identifier of the node.
     */
    protected string $id;

    /**
     * Attributes of the node.
     */
    protected AttributeBag $attributes;

    /**
     * Creates a new node.
     *
     * @param string                       $id         Identifier of the node
     * @param array<string,string|RawText> $attributes Attributes to set on node
     * @param AbstractGraph                $parent     Parent instruction
     */
    public function __construct(AbstractGraph $parent, string $id, array $attributes = [])
    {
        $this->parent = $parent;
        $this->id = $id;
        $this->attributes = new AttributeBag($attributes);
    }

    /**
     * Returns the identifier of the node.
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Returns attributes of the graph.
     */
    public function getAttributeBag(): AttributeBag
    {
        return $this->attributes;
    }

    /**
     * Returns the value of an attribute of the node.
     *
     * @param string              $name    name of the attribute
     * @param string|RawText|null $default default value if the attribute does not exist
     */
    public function getAttribute(string $name, string|RawText|null $default = null): string|RawText|null
    {
        return $this->attributes->get($name, $default);
    }

    /**
     * Sets an attribute of node.
     *
     * @param string         $name  Name of the attribute to set
     * @param string|RawText $value Value to set
     */
    public function attribute(string $name, string|RawText $value): Node
    {
        $this->attributes->set($name, $value);

        return $this;
    }

    /**
     * Fluid interface method.
     */
    public function end(): AbstractGraph
    {
        return $this->parent;
    }
}
