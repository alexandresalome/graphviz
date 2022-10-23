<?php

namespace Graphviz\Output;

use Graphviz\AbstractGraph;

interface RendererInterface
{
    public function render(AbstractGraph $graph): string;
}
