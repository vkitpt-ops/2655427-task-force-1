<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use TaskForce\Import\ImportRunner;

$importer = new ImportRunner();

$importer->run(
    'data/categories.csv',
    'category',
    'sql/category.sql'
);

$importer->run(
    'data/cities.csv',
    'city',
    'sql/city.sql'
);
