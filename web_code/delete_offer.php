<?php

include 'config.php';
require_once 'vendor/autoload.php';

session_start();

// Get the data from the POST request

$product = $_POST['product'];
$shop = $_POST['shop'];
$username = $_POST['username'];

$databaseName = 'web_project_database';
$collectionName = 'offers';

$client2 = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client2->$databaseName->$collectionName;
$delete_res = $collection->deleteOne([
    'shop' => $shop,
    'product_name' => $product,
    'username' => $username
]);

if ($delete_res->getDeletedCount() > 0){
    $response_mes = 'Successful delete of offer';
}else{
    $response_mes = 'Error on delete of offer';
}

echo json_encode($response_mes);
