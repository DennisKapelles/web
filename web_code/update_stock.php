<?php

include 'config.php';
require_once 'vendor/autoload.php';

session_start();

// Get the data from the POST request

$product = $_POST['product'];
$stock = $_POST['stock'];
$shop_name = $_POST['shop_name'];


$databaseName = 'web_project_database';
$collectionName = 'offers';

//Stock update
$client = new MongoDB\Client('mongodb://localhost:27017');
$collection = $client->$databaseName->$collectionName;
$fi = ['$and' => [    ['shop' => $shop_name],
    ['product_name' => $product]]];
$update = ['$set' => ['stock' => $stock]];
$update_res = $collection->updateMany($fi, $update);
$response_message = "Successful update of stock!";

echo json_encode($response_message);