<?php

include 'config.php';
require_once 'vendor/autoload.php';

session_start();

// Get the data from the POST request

// Get the "shop" value from the POST data
$shop = $_POST['shop'];

$databaseName = 'web_project_database';
$collectionName = 'offers';

$client2 = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client2->$databaseName->$collectionName;

// Find the records that match the specified "shop" value
$cursor = $collection->find(['shop' => $shop]);

// Iterate through the results and return the data as an array
$results = [];
foreach ($cursor as $document) {
    $results[] = $document;
}

// Return the data as a JSON object
echo json_encode($results);
