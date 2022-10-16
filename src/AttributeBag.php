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
 * Attribute holder for nodes and edges.
 *
 * @author Alexandre Salomé <graphviz@pub.salome.fr>
 */
class AttributeBag implements \Countable
{
    /** @var array<string, string|RawText> Associative array of attributes. The key is the name. */
    protected array $attributes = [];

    /**
     * Creates a new attribute bag.
     *
     * @param array<string, string|RawText> $attributes An associative array of attributes values
     */
    public function __construct(array $attributes = [])
    {
        foreach ($attributes as $name => $value) {
            $this->set($name, $value);
        }
    }

    /**
     * Changes the value of an attribute.
     *
     * @param string         $name  The name for the attribute
     * @param string|RawText $value Value to set
     *
     * @return self Fluid interface
     */
    public function set(string $name, string|RawText $value): self
    {
        $this->attributes[$name] = $value;

        return $this;
    }

    /**
     * Returns the value of an attribute.
     *
     * @param string              $name    The name for the attribute
     * @param string|RawText|null $default default value if attribute is not set
     *
     * @return string|RawText|null The attribute value
     */
    public function get(string $name, string|RawText|null $default = null): string|RawText|null
    {
        return $this->attributes[$name] ?? $default;
    }

    /**
     * Tests if the bag has an attribute.
     *
     * @param string $name The attribute name to check
     *
     * @return bool Result of the test
     */
    public function has(string $name): bool
    {
        return isset($this->attributes[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return count($this->attributes);
    }

    /**
     * @return array<string, string|RawText>
     */
    public function all(): array
    {
        return $this->attributes;
    }
}
