<?php

namespace Main;

use Symfony\Component\Finder\Finder;
use Graphviz\Dev\Documentation\ImageRenderer;

require_once __DIR__.'/../vendor/autoload.php';

$finder = Finder::create()
    ->in(__DIR__.'/../docs')
    ->name('*.md')
;

$renderer = new ImageRenderer(
    dirname(__DIR__).'/docs',
    '_gifs',
);

foreach ($finder as $file) {
    $renderer->renderImages($file->getRealPath());
}
