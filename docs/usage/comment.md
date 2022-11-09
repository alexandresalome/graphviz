---
title: Add comments
---

Adding comments
===============

Add comments into the graph
---------------------------

### Single line comments

You can add a comment inside your graph by using the method ``commentLine``:

```php
$graph = new Graphviz\Digraph();
$graph->commentLine('Empty graph');
echo $graph->render();

# digraph G {
#     // Empty graph
# }
```

Note that if you pass multiple lines to this method, it will add multiple lines:

```php
$graph = new Graphviz\Digraph();
$graph->commentLine("Line 1\nLine 2");
echo $graph->render();

# digraph G {
#     // Line 1
#     // Line 2
# }
```

You can also remove the initial space prefix by passing ``false`` as a second
argument:

```php
$graph = new Graphviz\Digraph();
$graph->commentLine('-- ASCII MASTER --//', false);
echo $graph->render();

# digraph G {
#     //-- ASCII MASTER --//
# }
```

And for C++ style arguments (``#`` instead of ``//``), pass ``true`` as a third
argument:

```php
$graph = new Graphviz\Digraph();
$graph->commentLine('C++ style', true, false);
echo $graph->render();

# digraph G {
#     # C++ style
# }
```

### Block comments

You can add block comments by using the ``commentBlock`` function:

```php
$graph = new Graphviz\Digraph();
$graph->commentBlock('My block comment');
echo $graph->render();

# digraph G {
#     /*
#      * My block comment
#      */
# }
```

Multiple lines is also supported:

```php
$graph = new Graphviz\Digraph();
$graph->commentBlock("My block comment\non multiple lines");
echo $graph->render();

# digraph G {
#     /*
#      * My block comment
#      * on multiple lines
#      */
# }
```



You can disable new lines and spacing and new line indent by providing ``false``
as a second  argument:

```php
$graph = new Graphviz\Digraph();
$graph->commentBlock("** ASCII fan\nNew line\n**", false);
echo $graph->render();

# digraph G {
#     /*** ASCII fan
# New line\n
# ***/
# }
```
