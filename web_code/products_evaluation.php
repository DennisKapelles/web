<?php

include 'config.php';
require_once 'vendor/autoload.php';

session_start();

// Get the data from the POST request

$shop_name = $_POST['shop_name'];

// Get from database the offers of the requested shop for search box
$filter=['shop' => $shop_name];
$options=[];
$query = new MongoDB\Driver\Query($filter, $options);
$result = $client->executeQuery('web_project_database.offers', $query);

$products = [];
foreach($result as $res){
    $product_name = $res->product_name;
    $products[] = $product_name;
}
echo json_encode($products);
