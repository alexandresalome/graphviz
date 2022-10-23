---
title: Render a graph
---

Rendering a graph
=================

Once you have created your graph, you can render the dot file by using the
``render()`` method available on the *Graph* objects:

```php
$graph = new Graphviz\Digraph();

$graph->edge(['Alice', 'Bob']);

echo $graph->render();
```

This script will output the following:

```
digraph G {
    Alice -> Bob;
}
```

This file is in **DOT** language, the format used by Graphviz.

Indentation of the output
-------------------------

You can change the indentation of the graph by using the
``Graphviz\Output\DotRenderer`` explicitly:

```php
$renderer = new Graphviz\Output\DotRenderer('  ');

echo $renderer->render($graph);
```

The first argument of the ``DotRenderer`` is the indentation to use for the
graph. With this example, the output will be:

```
digraph G {
  Alice -> Bob;
}
```
