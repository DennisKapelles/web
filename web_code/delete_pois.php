<?php

session_start();

// Delete data from collection pois_2
$bulk = new MongoDB\Driver\BulkWrite;
$bulk->delete([]);
$manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');
$result = $manager->executeBulkWrite('web_project_database.pois_2', $bulk);

// Delete data from collection POIS
$bulk1 = new MongoDB\Driver\BulkWrite;
$bulk1->delete([]);
$manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');
$result = $manager->executeBulkWrite('web_project_database.POIS', $bulk1);

// Redirecting back
header("Location:".$_SERVER["HTTP_REFERER"]);
