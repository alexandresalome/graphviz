<?php
/*
 * This file is part of PHP Graphviz.
 * (c) Alexandre Salomé <graphviz@pub.salome.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Graphviz\Tests;

use Graphviz\AttributeSet;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Graphviz\AttributeSet
 */
class AttributeSetTest extends TestCase
{
    public function testGetters(): void
    {
        $set = new AttributeSet('node', ['foo' => 'bar']);

        $this->assertSame('node', $set->getName(), 'getName');
        $this->assertSame('bar', $set->getAttributeBag()->get('foo'), 'Attributes');
    }

    /**
     * @dataProvider provideIncorrectElement
     */
    public function testElement(string $element, bool $isCorrect): void
    {
        if (!$isCorrect) {
            $this->expectException(\InvalidArgumentException::class);
        }
        new AttributeSet($element);
        $this->assertTrue(true); // avoid risky test
    }

    /**
     * @return array<array{string, bool}>
     */
    public function provideIncorrectElement(): array
    {
        return [
            ['node', true],
            ['edge', true],
            ['graph', true],
            ['foo', false],
        ];
    }
}
