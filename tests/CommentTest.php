<?php
/*
 * This file is part of PHP Graphviz.
 * (c) Alexandre Salomé <graphviz@pub.salome.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Graphviz\Tests;

use Graphviz\Comment;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Graphviz\Comment
 */
class CommentTest extends TestCase
{
    public function testGetContent(): void
    {
        $comment = new Comment('// foo');
        $this->assertSame('// foo', $comment->getContent());
    }

    public function testIsIndented(): void
    {
        $comment = new Comment('// foo');
        $this->assertTrue($comment->isIndented());
        $comment = new Comment('// foo', false);
        $this->assertFalse($comment->isIndented());
    }
}
