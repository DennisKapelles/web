<?php

include 'config.php';
require_once 'vendor/autoload.php';

session_start();

// Get the data from the POST request

$old_user_name = $_POST['session_Username'];
$new_password = $_POST['new_password'];

$databaseName = 'web_project_database';
$collectionName1 = 'users';

$client2 = new MongoDB\Client("mongodb://localhost:27017");
$collection1 = $client2->$databaseName->$collectionName1;

// Create the filter and update values
$filter = ['username' => $old_user_name];
$update = ['$set' => ['password' => $new_password]];

// Use the updateOne method to update the record
$result_user = $collection1->updateOne($filter, $update);
$response_message = "Successful password change";

echo json_encode($response_message);