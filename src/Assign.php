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
 * Graph attribute assignment.
 *
 * @author Alexandre Salomé <graphviz@pub.salome.fr>
 */
class Assign implements InstructionInterface
{
    /**
     * Creates a new assignment.
     *
     * @param string         $name  Name of the attribute
     * @param string|RawText $value Value of the attribute
     */
    public function __construct(
        protected string $name,
        protected string|RawText $value = ''
    ) {
    }

    /**
     * Returns the name of assignment.
     *
     * @return string The assignment name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns the value of assignment.
     *
     * @return string|RawText the assignment value
     */
    public function getValue(): string|RawText
    {
        return $this->value;
    }

    /**
     * Changes the value of assignment.
     *
     * @param string|RawText $value The new value to set
     *
     * @return Assign Fluid interface
     */
    public function setValue(string|RawText $value): self
    {
        $this->value = $value;

        return $this;
    }
}
