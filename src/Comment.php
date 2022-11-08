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
 * Comment.
 *
 * @author Alexandre Salomé <graphviz@pub.salome.fr>
 */
class Comment implements InstructionInterface
{
    /** @var string Content of the comment */
    protected string $content;

    /**
     * Creates a new comment.
     *
     * @param string $content Comment content, with delimiters
     */
    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
