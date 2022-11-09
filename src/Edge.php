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
 * Base edge class.
 *
 * @author Alexandre Salomé <graphviz@pub.salome.fr>
 */
class Edge implements InstructionInterface
{
    /**
     * Parent instruction.
     */
    protected AbstractGraph $parent;

    /**
     * List of element identifiers.
     *
     * @var array<string|array<string>>
     */
    protected array $list;

    /**
     * Attributes of the edge.
     */
    protected AttributeBag $attributes;

    /**
     * Creates an edge.
     *
     * @param AbstractGraph                 $parent     Parent instruction
     * @param array<string|array<string>>   $list       List of edges
     * @param array<string, string|RawText> $attributes Associative array of attributes
     */
    public function __construct(AbstractGraph $parent, array $list, array $attributes = [])
    {
        $this->parent = $parent;
        $this->list = $list;
        $this->attributes = new AttributeBag($attributes);
    }

    /**
     * Returns list of elements composing the edge.
     *
     * @return array<string|array<string>>
     */
    public function getPath(): array
    {
        return $this->list;
    }

    /**
     * Returns the value of an attribute of the edge.
     *
     * @param string         $name    name of the attribute
     * @param string|RawText $default default value if the attribute does not exist
     *
     * @return string|RawText the value of the attribute
     */
    public function getAttribute(string $name, string|RawText $default = null): string|RawText
    {
        return $this->attributes->get($name, $default);
    }

    /**
     * Sets an attribute.
     *
     * @param string         $name  Name of the attribute to set
     * @param string|RawText $value Value of the attribute to set
     *
     * @return self Fluid-interface
     */
    public function attribute(string $name, string|RawText $value): self
    {
        $this->attributes->set($name, $value);

        return $this;
    }

    /**
     * Returns list of attributes.
     */
    public function getAttributeBag(): AttributeBag
    {
        return $this->attributes;
    }

    public function isDirected(): bool
    {
        return $this->parent->isDirected();
    }

    /**
     * Returns the parent, if available.
     *
     * @return AbstractGraph The parent or null
     */
    public function end(): AbstractGraph
    {
        return $this->parent;
    }
}
