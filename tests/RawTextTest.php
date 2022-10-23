<?php
/*
 * This file is part of PHP Graphviz.
 * (c) Alexandre Salomé <graphviz@pub.salome.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Graphviz\Tests;

use Graphviz\RawText;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Graphviz\RawText
 */
class RawTextTest extends TestCase
{
    public function testGetText(): void
    {
        $text = new RawText('foo');
        $this->assertSame('foo', $text->getText());
    }
}
