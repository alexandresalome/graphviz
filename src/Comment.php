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

    /** @var bool Indent the comment on rendering */
    protected bool $indented;

    /**
     * Creates a new comment.
     *
     * @param string $content  Comment content, with delimiters
     * @param bool   $indented Indicates if the content must be rendered indented
     */
    public function __construct(string $content, bool $indented = true)
    {
        $this->content = $content;
        $this->indented = $indented;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function isIndented(): bool
    {
        return $this->indented;
    }
}
