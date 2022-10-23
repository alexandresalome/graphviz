---
title: Setting attributes
---

Setting attributes
==================

Graph attributes
----------------

To define an attribute on your graph, use the ``set`` method:

<!-- Graph -->
```php
# Directed graph
$graph = new Graphviz\Digraph();
$graph->set('bgcolor', 'red');
$graph->nodes(['foo', 'bar']);
```
![Rendering of the code above](_gifs/attributes.0.png)

Node, edge, and graph attributes
--------------------------------

The DOT language allows you to define attributes that are used for all the
following nodes, edges, or for the current graph:

<!-- Graph -->
```php
# Directed graph
$graph = new Graphviz\Digraph();
$graph
    ->attr('node', ['style' => 'filled', 'fillcolor' => 'yellow'])
    ->attr('edge', ['color' => 'blue'])
    ->nodes(['foo', 'bar'])
    ->subgraph('cluster_baz')
        ->attr('graph', ['bgcolor' => 'green'])
        ->nodes(['A', 'B', 'C'])
    ->end()
    ->edge(['foo', 'B'])
    ->edge(['bar', 'C'])
;
```
![Rendering of the code above](_gifs/attributes.1.png)
