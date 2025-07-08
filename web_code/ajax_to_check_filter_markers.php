<?php

include 'config.php';
require_once 'vendor/autoload.php';

session_start();
$query = new MongoDB\Driver\Query([],[]);
$resu = $client->executeQuery('web_project_database.offers', $query);
$offers_shop_names = [];

foreach($resu as $res1){
    $stored_shop = $res1->shop;
    $offers_shop_names[] = $stored_shop;
}

echo json_encode($offers_shop_names);
