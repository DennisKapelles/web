<?php

include 'config.php';
require_once 'vendor/autoload.php';

session_start();

// Get the data from the POST request

$old_user_name = $_POST['session_Username'];
$new_user_name = $_POST['new_username'];

$databaseName = 'web_project_database';
$collectionName1 = 'users';
$collectionName2 = 'Score';
$collectionName3 = 'tokens';
$collectionName4 = 'offers';
$collectionName5 = 'logs';

$client2 = new MongoDB\Client("mongodb://localhost:27017");
$collection1 = $client2->$databaseName->$collectionName1;
$collection2 = $client2->$databaseName->$collectionName2;
$collection3 = $client2->$databaseName->$collectionName3;
$collection4 = $client2->$databaseName->$collectionName4;
$collection5 = $client2->$databaseName->$collectionName5;


// Create the filter and update values
$filter = ['username' => $old_user_name];
$update = ['$set' => ['username' => $new_user_name]];

// Use the updateOne method to update the record
$result_user = $collection1->updateOne($filter, $update);
$result_score = $collection2->updateOne($filter, $update);
$result_tokens = $collection3->updateOne($filter, $update);
$result_offers = $collection4->updateMany($filter, $update);
$result_logs = $collection5->updateOne($filter, $update);
$response_message = "Successful username change";

echo json_encode($response_message);