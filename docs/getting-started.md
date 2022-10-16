---
title: Getting started
---

Getting started
===============

Installation
------------

Add **PHP Graphviz** as a dependency to your project using
[Composer](https://getcomposer.org).

In the root directory of your project, run:

```shell
composer require alom/graphviz
```

Render a graph
--------------

In your project, create a file ``test-graphviz.php``, in the directory of your
``composer.json``:

```php
<?php

require_once __DIR__.'/vendor/autoload.php';

$graph = new Graphviz\Digraph();

$graph
    ->subgraph('cluster_1')
        ->attr('node', ['style' => 'filled', 'fillcolor' => 'blue'])
        ->node('A')
        ->node('B')
    ->end()
    ->edge(array('A', 'B', 'C'))
;

echo $graph->render();
```

This script, when executed, will render the following:

```
digraph G {
    subgraph cluster_1 {
        node [style=filled, fillcolor=blue];
        A;
        B;
    }
    A -> B -> C;
}
```

Convert graph to an image
-------------------------

This library only render **dot** language. If you want to generate an image out
of it, you must install [graphviz](https://graphviz.org/) and run the following
command:

```shell
php test-graphviz.php | dot -Tpng > test-graphviz.png
```
