<?php

include 'config.php';

session_start();

$session_username = $_POST['session_Username'];

$filter=['username'=>$session_username];
$options=[];
$query = new MongoDB\Driver\Query($filter, $options);
$result = $client->executeQuery('web_project_database.Score', $query);
$score_array = [];

foreach($result as $res){

    $stored_total_score = $res->total_score;
    $stored_current_score = $res->current_score;
    $score = [$stored_current_score,$stored_total_score];

    $score_array[]= $score;

}

echo json_encode($score_array);
