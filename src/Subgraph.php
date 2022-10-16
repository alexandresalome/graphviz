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
 * Subgraph.
 *
 * @author Alexandre Salomé <graphviz@pub.salome.fr>
 */
class Subgraph extends AbstractGraph
{
    public function __construct(AbstractGraph $parent, string $id = 'G')
    {
        parent::__construct($id, $parent);
    }

    public function isDirected(): bool
    {
        return $this->parent->isDirected();
    }
}
