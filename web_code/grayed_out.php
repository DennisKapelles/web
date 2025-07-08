<?php

include 'config.php';
require_once 'vendor/autoload.php';

session_start();

// Get the data from the POST request

$shop = $_POST['shop'];
$product = $_POST['product'];

// Get the stock of the requested product in order to gray out like and dislike buttons
$filter=['shop' => $shop, 'product_name' => $product];
$options=[];
$query = new MongoDB\Driver\Query($filter, $options);
$result = $client->executeQuery('web_project_database.offers', $query);

$products = [];
foreach($result as $res){
    $stock = $res->stock;
    $products[] = $stock;
}
echo json_encode($products);