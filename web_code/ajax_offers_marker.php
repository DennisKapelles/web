<?php

include 'config.php';

session_start();

$filter=[];
$options=[];
$query = new MongoDB\Driver\Query($filter, $options);
$result = $client->executeQuery('web_project_database.offers', $query);

$pois = [];
foreach($result as $res){
    $shop_name = $res->shop;
    $product_name = $res->product_name;

    //=================new update=============
    $price = $res->price;
    $likes = $res->likes;
    $dislikes = $res->dislikes;
    $offer_date = $res->offer_date;
    $stock = $res->stock;
    $criteria = $res->criteria;

    $quer = new MongoDB\Driver\Query(["name"=>$shop_name], []);
    $resul = $client->executeQuery('web_project_database.POIS', $quer);

    foreach($resul as $re){
        $lat = $re->coordinates[1];
        $lon = $re->coordinates[0];
        //$poi = [$shop_name,$lat,$lon,$product_name];
        $poi = [$shop_name,$lat,$lon,$product_name,$price,$likes,$dislikes,$stock,$offer_date, $criteria];
        $pois[] = $poi;
    }
}
echo json_encode($pois);