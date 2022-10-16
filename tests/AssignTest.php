<?php
/*
 * This file is part of PHP Graphviz.
 * (c) Alexandre Salomé <graphviz@pub.salome.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Graphviz\Tests;

use Graphviz\Assign;
use Graphviz\RawText;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Graphviz\Assign
 */
class AssignTest extends TestCase
{
    public function testValue(): void
    {
        $assign = new Assign('foo');
        $this->assertSame('', $assign->getValue(), 'Default value');

        $return = $assign->setValue('bar');
        $this->assertSame('bar', $assign->getValue(), 'Value getter is correct');
        $this->assertSame($return, $assign);
    }

    public function testValueRawText(): void
    {
        $assign = new Assign('foo', new RawText('<table>'));
        $value = $assign->getValue();
        $this->assertInstanceOf(RawText::class, $value);
        $this->assertSame('<table>', $value->getText());
    }

    public function testName(): void
    {
        $assign = new Assign('foo');
        $this->assertSame('foo', $assign->getName(), 'name getter');
    }
}
