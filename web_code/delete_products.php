<?php

session_start();

// Delete data from collection prod and categ
$bulk = new MongoDB\Driver\BulkWrite;
$bulk->delete([]);
$manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');
$result = $manager->executeBulkWrite('web_project_database.prod and categ', $bulk);
//$deleteResult = $manager -> $web_project_database->prod and categ->deleteMany([]);

// Delete data from collection products
$bulk1 = new MongoDB\Driver\BulkWrite;
$bulk1->delete([]);
$manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');
$result = $manager->executeBulkWrite('web_project_database.products', $bulk1);

// Delete data from collection categories
$bulk2 = new MongoDB\Driver\BulkWrite;
$bulk2->delete([]);
$manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');
$result = $manager->executeBulkWrite('web_project_database.categories', $bulk2);

// Delete data from collection prices
$bulk3 = new MongoDB\Driver\BulkWrite;
$bulk3->delete([]);
$manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');
$result = $manager->executeBulkWrite('web_project_database.prices', $bulk3);

// Delete data from collection Avg_prices
$bulk4 = new MongoDB\Driver\BulkWrite;
$bulk4->delete([]);
$manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');
$result = $manager->executeBulkWrite('web_project_database.Avg_prices', $bulk4);

// Redirecting back
header("Location:".$_SERVER["HTTP_REFERER"]);
