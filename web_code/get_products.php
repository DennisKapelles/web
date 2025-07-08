<?php

include 'config.php';

session_start();

$filter=[];
$options=[];
$query = new MongoDB\Driver\Query($filter, $options);
$result = $client->executeQuery('web_project_database.products', $query);

$products = [];
foreach($result as $res){
    $product_name = $res->name;
    $products[] = $product_name;
    //array_push($coord, $lat, $lon);
    //print_r($coord);
    //echo "<br>";
}
echo json_encode($products);
//print_r($products);
