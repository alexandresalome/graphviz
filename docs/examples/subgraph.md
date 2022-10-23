---
title: Subgraph
---

Subgraph examples
=================

Cluster
-------

<!-- Graph -->
```php
$graph = new Graphviz\Digraph();
$graph
    ->nodes(['A', 'B', 'C'])
    ->subgraph('cluster_A')
        ->set('bgcolor', '#ffcc00')
        ->set('label', 'Title of the cluster A')
        ->nodes(['D', 'E'])
    ->end()
    ->subgraph('cluster_B')
        ->nodes(['F', 'G'])
    ->end()
    ->edge(['A', 'C'])
    ->edge(['B', 'D', 'F'])
    ->edge(['D', 'E'])
    ->edge(['B', 'G'])
;
```
![Rendering of the code above](_gifs/subgraph.0.png)

Nested graph
------------

<!-- Graph -->
```php
$graph = new Graphviz\Digraph();
$graph->node('A')->edge(['A', 'B']);

$sub1 = $graph
    ->subgraph('cluster_1')
    ->node('B')
    ->edge(['B', 'C'])
;

$sub2 = $sub1->subgraph('cluster_2')
    ->node('C')
    ->edge(['C', 'D'])
;

$sub3 = $sub2->subgraph('cluster_3')
    ->node('D')
;
```
![Rendering of the code above](_gifs/subgraph.1.png)
