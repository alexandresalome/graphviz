<?php
/*
 * This file is part of Alom Graphviz.
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alom\Graphviz\Tests;

use Alom\Graphviz\Node;

class NodeTest extends \PHPUnit_Framework_TestCase
{
    public function testRender()
    {
        $node = new Node('A');

        $this->assertEquals("A;\n", $node->render(), "Render basic");
    }

    public function testTitle()
    {
        $node = new Node('B');
        $node->attribute('label', 'normal label text');
        $this->assertEquals("A;\n", $node->render(), "Render basic");
    }

    /**
     * Test the record type label form.
     *
     * @see http://graphviz.org/content/node-shapes#record
     */
    public function testTitleRecord()
    {
        $node = new Node('B');
        $node->attribute('label', '<f0> left|<f1> mid&#92; dle|<f2> right');
        $node->attribute('shape', 'record');
        $this->assertEquals("A;\n", $node->render(), "Render basic");
    }

    /**
     * Test the HTML label form.
     *
     * @see http://graphviz.org/content/node-shapes#html
     */
    public function testTitleTable()
    {
        $node = new Node('B');
        $node->attribute('label', '<<TABLE><TR><TD>left</TD><TD PORT="f1">mid dle</TD><TD PORT="f2">right</TD></TR></TABLE>>');
        $this->assertEquals("A;\n", $node->render(), "Render basic");
    }

}
