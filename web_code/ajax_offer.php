<?php

include 'config.php';
require_once 'vendor/autoload.php';

session_start();


// Get from database the offers of the requested shop for search box
$filter=[];
$options=[];
$query = new MongoDB\Driver\Query($filter, $options);
$result = $client->executeQuery('web_project_database.offers', $query);

$products = [];
foreach($result as $res){
    $product_name = $res->product_name;
    $products[] = $product_name;
}
echo json_encode($products);
