<?php
/*
 * This file is part of PHP Graphviz.
 * (c) Alexandre Salomé <graphviz@pub.salome.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Graphviz\Tests\Output;

use Graphviz\Output\DotEscaper;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Graphviz\Output\DotEscaper
 */
class DotEscaperTest extends TestCase
{
    /**
     * @dataProvider provideEscape
     */
    public function testEscape(string $input, string $expected): void
    {
        $actual = (new DotEscaper())->escape($input);
        $this->assertSame($expected, $actual);
    }

    /**
     * @return string[][]
     */
    public function provideEscape(): array
    {
        return [
            ['foo', 'foo'],
            ['foo bar', '"foo bar"'],
            ['{foo', '"{foo"'],
            ['foo}', '"foo}"'],
            ['<foo', '"<foo"'],
            ['foo>', '"foo>"'],
            ['foo"', '"foo"""'],
            ['foo#', '"foo#"'],
            ['foo-', '"foo-"'],
            ['foo:', '"foo:"'],
            ['foo\\', '"foo\\\\"'],
            ['foo/', '"foo/"'],
            ['foo.', '"foo."'],
            ['foo,', '"foo,"'],
            ['graph', '"graph"'],
            ['node', '"node"'],
            ['edge', '"edge"'],
            ['', '""'],
            ['2', '2'],
        ];
    }

    public function testEscapePath(): void
    {
        $actual = (new DotEscaper())->escapePath(['foo', 'foo bar']);
        $this->assertSame('foo:"foo bar"', $actual);
    }
}
