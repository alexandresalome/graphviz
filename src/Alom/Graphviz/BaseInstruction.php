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
 * Base class for Graphviz instructions.
 *
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
abstract class BaseInstruction implements InstructionInterface
{
    /**
     * Renders an inline assignment (without indent or end return line).
     *
     * It will handle escaping, according to the value.
     *
     * @param string $name  A name
     * @param string $value A value
     *
     * @return string
     */
    protected function renderInlineAssignment($name, $value)
    {
        if ($value instanceof RawText) {
            $value = $value->getText();
        } else {
            $value = $this->escape($value);
        }

        return $this->escape($name).'='.$value;
    }

    /**
     * Escapes a value if needed.
     *
     * @param string $value The value to set
     *
     * @return string The escaped string
     */
    protected function escape($value)
    {
        return ($this->needsEscaping($value)) ? '"' . str_replace('"', '""', str_replace('\\', '\\\\', $value)) . '"' : $value;
    }

    protected function escapePath(array $path)
    {
        $list = array();
        foreach ($path as $element) {
            $list[] = $this->escape($element);
        }

        return implode(':', $list);
    }

    /**
     * Tests if a string needs escaping.
     *
     * @param string $value
     *
     * @return boolean Result of test
     */
    protected function needsEscaping($value)
    {
        if (in_array(strtolower($value), ['node', 'edge', 'graph'], true)) {
            return true;
        }

        return preg_match('/[{} "#-:\\\\\\/\\.,]/', $value) || in_array($value, array('graph', 'node', 'edge')) || empty($value);
    }
}
