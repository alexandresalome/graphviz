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
 * Attributes bag for node/edge/graph
 *
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class AttributeSet extends BaseInstruction
{
    /**
     * Name of shape to set attributes
     *
     * @param string
     */
	protected $name;

    /**
     * Attribute bag
     *
     * @param AttributeBag
     */
	protected $attributes;

    /**
     * Creates a new attribute set
     *
     * @param string $name Name of the attribute set
     */
	public function __construct($name, array $attributes = array())
	{
        if (!in_array($name, array('node', 'edge', 'graph'))) {
            throw new \InvalidArgumentException(sprintf('Name invalid for attribute set : %s', $name));
        }

		$this->name = $name;
		$this->attributes = new AttributeBag($attributes);
	}

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
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
        $spaces = str_repeat($spaces, $indent);

		return $spaces.$this->name.' '.$this->attributes->render().";\n";
	}
}
