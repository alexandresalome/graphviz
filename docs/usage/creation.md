---
title: Create a graph
---

Creating a graph
================

To create a graph, instantiate a ``Graphviz\Digraph`` or a ``Graphviz\Graph``
object:

```php
# Directed graph
$graph = new Graphviz\Digraph();

# Undirected graph
$graph = new Graphviz\Graph();
```

Those two objects are mostly identical, and methods documented in the next
chapters are available for both objects.

Directed graph
--------------

A directed graph can be created by using the ``Graphviz\Digraph`` class when you
instantiate your graph:

<!-- Graph -->
```php
$graph = new Graphviz\Digraph();

$graph->set('rankdir', 'LR');
$graph->edge(['Alice', 'Bob', 'Charlie']);
$graph->edge(['Alice', 'Charlie']);
```
![Rendering of the code above](_gifs/creation.0.png)

Undirected graph
----------------

An undirected graph can be created by using the ``Graphviz\Graph`` class when
you instantiate your graph:

<!-- Graph -->
```php
$graph = new Graphviz\Graph();

$graph->set('rankdir', 'LR');
$graph->edge(['Alice', 'Bob', 'Charlie']);
$graph->edge(['Alice', 'Charlie']);
```
![Rendering of the code above](_gifs/creation.1.png)
