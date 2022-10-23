---
title: Nodes
---

Nodes
=====

Different methods are available on the graph objects to create a node.

Creating a single node
----------------------

You can simply call ``node`` with the identifier of the node you want to create:

<!-- Graph -->
```php
$graph = new Graphviz\Digraph();

$graph->node('foo');
```
![Rendering of the code above](_gifs/nodes.0.png)

This method returns the Graph object, so you can chain multiple calls:

<!-- Graph -->
```php
$graph = new Graphviz\Digraph();

$graph
    ->node('foo')
    ->node('bar')
;
```
![Rendering of the code above](_gifs/nodes.1.png)

Passing attributes to a node
----------------------------

To provide attributes to a node, pass them as a second array to this method
with an association between the parameter names and the parameter values:

<!-- Graph -->
```php
$graph = new Graphviz\Digraph();

$graph->node('foo', [
    'shape' => 'record',
    'label' => 'This is the label of the node'
]);
```
![Rendering of the code above](_gifs/nodes.2.png)

Creating multiple nodes
-----------------------

A method named ``nodes`` is available for you to create multiple nodes at once:

<!-- Graph -->
```php
$graph = new Graphviz\Digraph();

$graph->nodes(['foo', 'bar', 'baz']);
```
![Rendering of the code above](_gifs/nodes.3.png)

You can pass attributes to a node by providing it an associative array:

<!-- Graph -->
```php
$graph = new Graphviz\Digraph();

$graph->nodes([
    'foo',
    'bar' => ['shape' => 'record'],
    'baz',
]);
```
![Rendering of the code above](_gifs/nodes.4.png)

The `Node` object
-----------------

The ``node`` method is fluid, meaning it returns the Graph object so that you
can chain multiple calls. But you can also call the ``beginNode`` method, so
that you have the object for manipulation:

<!-- Graph -->
```php
$graph = new Graphviz\Digraph();
$node = $graph->beginNode('foo');

# Set a value
$node->attribute('shape', 'record');
$node->attribute('label', 'This is the label');

# Get a value
$value = $node->getAttribute('shape');
```
![Rendering of the code above](_gifs/nodes.5.png)

Accessing an existing `Node` object
-----------------------------------

If you already added a `Node` to your graph, you can access it, given you have
its identifier, using the ``get`` method:

<!-- Graph -->
```php
$graph = new Graphviz\Digraph();
$graph->node('foo');

$foo = $graph->get('foo');
$foo->attribute('shape', 'record');
```
![Rendering of the code above](_gifs/nodes.6.png)
