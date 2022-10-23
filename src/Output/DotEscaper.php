<?php
/*
 * This file is part of PHP Graphviz.
 * (c) Alexandre Salomé <graphviz@pub.salome.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Graphviz\Output;

class DotEscaper
{
    /**
     * Escapes a value if needed.
     *
     * @param string $value The value to escape
     *
     * @return string The escaped value
     */
    public function escape(string $value): string
    {
        return ($this->needsEscaping($value)) ? '"'.str_replace('"', '""', str_replace('\\', '\\\\', $value)).'"' : $value;
    }

    /**
     * Escapes a path (a list of nodes).
     *
     * @param string[] $path
     */
    public function escapePath(array $path): string
    {
        $list = [];
        foreach ($path as $element) {
            $list[] = $this->escape($element);
        }

        return implode(':', $list);
    }

    /**
     * Tests if a string needs escaping.
     *
     * @return bool Result of test
     */
    private function needsEscaping(string $value): bool
    {
        if (in_array(strtolower($value), ['node', 'edge', 'graph'], true)) {
            return true;
        }

        return preg_match('/[{}<> "#\-:\\\\\\/.,]/', $value) || '' === $value;
    }
}
