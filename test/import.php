<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use TaskForce\Import\ImportRunner;

$importer = new ImportRunner();

$importer->run(
    __DIR__ . '/../data/categories.csv',
    'category',
    __DIR__ . '/../sql/category.sql'
);

$importer->run(
    __DIR__ . '/../data/cities.csv',
    'city',
    __DIR__ . '/../sql/city.sql'
);

echo "Импорт завершен.\n";
