<?php

include 'config.php';
require_once 'vendor/autoload.php';

session_start();

$databaseName = 'web_project_database';
$collectionName = 'Score';

// Connect to MongoDB
$client2 = new MongoDB\Client("mongodb://localhost:27017");

$collection = $client2->$databaseName->$collectionName;

// Get offers data from database to put it on the table
//$filter=[];
//$options=[];
//$query = new MongoDB\Driver\Query($filter, $options);
//$result = $client->executeQuery('web_project_database.Score', $query);
$rank = 0;
$leader = [];

//Find the 5 usernames with the highest current_score in the 'score' collection
$cursor = $collection->find([], ['sort' => ['total_score' => -1]]);

foreach($cursor as $res){
    $username = $res->username;
    $totalscore = $res->total_score;
    $rank = $rank + 1;
    // Get users score data from database to put it on the table
    $quer = new MongoDB\Driver\Query(["username"=>$username], []);
    $resul = $client->executeQuery('web_project_database.tokens', $quer);

    foreach($resul as $re) {
        $premon_tokens = $re->tokens_previous_month_user;
        $total_tokens = $re->total_tokens_user;
        $poi = [$rank, $username, $totalscore, $premon_tokens, $total_tokens];
        $leader[] = $poi;
    }
}
echo json_encode($leader);
