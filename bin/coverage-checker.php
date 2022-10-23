<?php

$inputFile  = $argv[1];

if (!file_exists($inputFile)) {
    throw new InvalidArgumentException('Invalid input file provided');
}

$xml = new \DOMDocument();
$xml->load($inputFile);
$xpath = new \DOMXpath($xml);

$metrics = $xpath->query('//metrics');
$totalElements   = 0;
$checkedElements = 0;

$total = $covered = 0;
foreach ($metrics as $metric) {
    $total   += (int) $metric->getAttribute('elements');
    $covered += (int) $metric->getAttribute('coveredelements');
}

if ($total !== $covered) {
    $coverage = round(($covered/$total) * 100, 2);
    echo 'Code coverage is ' . $coverage . '%, which is below the accepted 100%'.PHP_EOL;
    exit(1);
}

echo 'Code coverage is 100% - OK!'.PHP_EOL;
