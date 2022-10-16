---
title: Subgraph
---

Subgraph examples
=================

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
