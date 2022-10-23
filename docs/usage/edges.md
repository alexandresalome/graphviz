---
title: Edges
---

Edges
=====

In a graph object, you can create edges using the `edge` method

Creating an edge
----------------

The method ``edge`` allows the creation of an edge with any number of node
identifiers:

<!-- Graph -->
```php
$graph = new Graphviz\Digraph();
$graph->set('rankdir', 'LR');
$graph->edge(['foo', 'bar', 'baz']);
```
![Rendering of the code above](_gifs/edges.0.png)

Edge attributes
---------------

You can pass attributes to your edge by providing a second argument with an
array associating names with values:

<!-- Graph -->
```php
$graph = new Graphviz\Digraph();
$graph->edge(['foo', 'bar'], [
    'style' => 'dashed',
    'color' => 'red',
]);
```
![Rendering of the code above](_gifs/edges.1.png)

Targeting a port identifier
---------------------------

If you have created a port identifier inside a record, you can make your edge
target this port by providing an array instead of a string.

<!-- Graph -->
```php
$graph = new Graphviz\Digraph();
$graph->node('week', [
    'shape' => 'record',
    'label' => 'Monday|Tuesday|<wed> Wednesday|Thursday|Friday',
]);

$graph->node('people', [
    'shape' => 'record',
    'label' => 'Alice|<bob> Bob|Charlie',
]);

$graph->edge([
    ['week', 'wed'],
    ['people', 'bob'],
]);
```
![Rendering of the code above](_gifs/edges.2.png)
