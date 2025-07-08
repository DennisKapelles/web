<?php

include 'config.php';
require_once 'vendor/autoload.php';

session_start();

$session_username = $_POST['session_Username'];
// Connect to the MongoDB server and select the database
$client2 = new MongoDB\Client('mongodb://localhost:27017');
$databaseName = 'web_project_database';
$collectionName = 'logs';
$collection = $client2->$databaseName->$collectionName;

// Retrieve the "history" field of the document with the specified username
$log = $collection->findOne(['username' => $session_username], ['projection' => ['user_history' => 1]]);

// Return the "history" field as a JSON object
echo json_encode($log);
