<?php

include 'config.php';
require_once 'vendor/autoload.php';

session_start();

// Get the data from the POST request

//$shop_name = $_POST['shop_name'];

// Get offers data from database to put it on the table
$filter=[];
$options=[];
$query = new MongoDB\Driver\Query($filter, $options);
$result = $client->executeQuery('web_project_database.offers', $query);

$pois = [];
foreach($result as $res){
    $product_name = $res->product_name;
    $shop_name = $res->shop;
    $username = $res->username;
    $price = $res->price;
    $likes = $res->likes;
    $dislikes = $res->dislikes;
    $stock = $res->stock;
    $offerdate = $res->offer_date;
    $expirydate = $res->expiry_date;
    $criteria = $res->criteria;

    // Get users score data from database to put it on the table
    $quer = new MongoDB\Driver\Query(["username"=>$username], []);
    $resul = $client->executeQuery('web_project_database.Score', $quer);

    foreach($resul as $re) {
        $score = $re->total_score;
        $poi = [$product_name, $shop_name, $username, $price, $likes, $dislikes, $stock, $offerdate, $expirydate, $criteria, $score];
        $pois[] = $poi;
    }
}
echo json_encode($pois);
