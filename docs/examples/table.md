---
title: Tables
---

Tables examples
===============

<!-- Graph -->
```php
$graph = new Graphviz\Digraph();
$graph
    ->node('escaped', [
        'label' => '<<table><tr><td>Should be escaped</td></tr></table>>',
    ])
    ->node('unescaped', [
        'label' => new Graphviz\RawText('<<table><tr><td>Should not</td><td>be escaped</td></tr></table>>'),
    ])
;
```
![Rendering of the code above](_gifs/table.0.png)
