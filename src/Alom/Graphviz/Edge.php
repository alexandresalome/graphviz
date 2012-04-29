<?php
/*
 * This file is part of Alom Graphviz.
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alom\Graphviz;

/**
 * Base edge class
 *
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
abstract class Edge extends BaseInstruction
{
    /**
     * List of elements
     *
     * @var array
     */
    protected $list;

    /**
     * Attributes of the edge
     *
     * @var AttributeBag
     */
    protected $attributes;

    /**
     * Parent instruction
     *
     * @var InstructionInterface
     */
    protected $parent;

    /**
     * Returns operator for associating elements
     *
     * @return string The operator
     */
    abstract protected function getOperator();

    /**
     * Creates an edge.
     *
     * @param array $list List of edges
     *
     * @param array $attributes Associative array of attributes
     *
     * @param InstructionInterface $parent Parent instruction
     */
    public function __construct(array $list, array $attributes = array(), InstructionInterface $parent = null)
    {
        $this->list = $list;
        $this->attributes = new AttributeBag($attributes);
        $this->parent = $parent;
    }

    /**
     * Returns list of elements composing the edge.
     *
     * @return array
     */
    public function getList()
    {
        return $this->list;
    }

    /**
     * Sets an attribute.
     *
     * @param string $name Name of the attribute to set
     *
     * @param string $value Value of the attribute to set
     *
     * @return Edge Fluid-interface
     */
    public function attribute($name, $value)
    {
        $this->attributes->set($name, $value);

        return $this;
    }

    /**
     * Returns list of attributes.
     *
     * @return AttributeBag
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @inheritdoc
     */
    public function render($indent = 0, $spaces = self::DEFAULT_INDENT)
    {
        $margin = str_repeat($spaces, $indent);

        $edges = array();
        foreach ($this->list as $edge) {
            $edges[] = $this->escape($edge);
        }

        $edge = implode($this->getOperator(), $edges);

        $attributes = $this->attributes->render($indent + 1);
        return sprintf("%s%s%s;\n", $margin, $edge, $attributes ? ' '.$attributes : $attributes);
    }

    /**
     * End function for fluid-interface.
     *
     * @return InstructionInterface|null The parent or null
     */
    public function end()
    {
        return $this->parent;
    }
}
