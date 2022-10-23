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
 * Attributes bag for node/edge/graph.
 *
 * @author Alexandre Salomé <graphviz@pub.salome.fr>
 */
class AttributeSet implements InstructionInterface
{
    /** @var string Name of shape to set attributes */
    protected string $name;

    /** @var AttributeBag Attribute bag */
    protected AttributeBag $attributes;

    /**
     * Creates a new attribute set.
     *
     * @param string                        $name       Name of the attribute set
     * @param array<string, string|RawText> $attributes
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(string $name, array $attributes = [])
    {
        if (!in_array($name, ['node', 'edge', 'graph'])) {
            throw new \InvalidArgumentException(sprintf('The name is invalid for an attribute set : "%s". Expected node, edge, or graph.', $name));
        }

        $this->name = $name;
        $this->attributes = new AttributeBag($attributes);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAttributeBag(): AttributeBag
    {
        return $this->attributes;
    }
}
