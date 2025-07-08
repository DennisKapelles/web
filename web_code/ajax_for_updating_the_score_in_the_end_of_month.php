<?php

include 'config.php';
require_once 'vendor/autoload.php';

session_start();

$databaseName = 'web_project_database';
$collectionName = 'Score';

// Connect to MongoDB
$client2 = new MongoDB\Client("mongodb://localhost:27017");

// Select the database and collection
$collection = $client2->$databaseName->$collectionName;

$first_day_this_month = date('01/m/Y'); // "01/01/2023"
$last_day_this_month  = date('t/m/Y');  // "31/01/2023"
$current_date = date("d/m/Y");          // "04/01/2023"
if($current_date == $last_day_this_month){

    // Find all documents in the collection
    $cursor = $collection->find([]);

    foreach ($cursor as $document) {

        // Update the document
        $updateResult = $collection->updateOne(
            ['_id' => $document['_id']],
            ['$inc' => ['total_score' => $document['current_score']], '$set' => ['score_previous_month' => $document['current_score'], 'current_score' => 0]]
        );
    }

}