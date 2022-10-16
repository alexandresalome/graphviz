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
 * Undirected graph.
 *
 * @author Alexandre Salomé <graphviz@pub.salome.fr>
 */
class Graph extends AbstractGraph
{
    public function __construct(string $id = 'G')
    {
        parent::__construct($id);
    }

    public function isDirected(): bool
    {
        return false;
    }
}
