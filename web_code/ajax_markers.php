<?php

include 'config.php';

session_start();

$filter=[];
$options=[];
$query = new MongoDB\Driver\Query($filter, $options);
$result = $client->executeQuery('web_project_database.POIS', $query);

$pois = [];
foreach($result as $res){
    $name = $res->name;
    $lat = $res->coordinates[1];
    $lon = $res->coordinates[0];

    $poi = [$name,$lat,$lon];
    $pois[] = $poi;
    //array_push($coord, $lat, $lon);
    //print_r($coord);
    //echo "<br>";
}
echo json_encode($pois);
