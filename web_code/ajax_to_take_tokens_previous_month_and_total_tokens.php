<?php

include 'config.php';

session_start();

$session_username = $_POST['session_Username'];

$filter=['username'=>$session_username];
$options=[];
$query = new MongoDB\Driver\Query($filter, $options);
$result = $client->executeQuery('web_project_database.tokens', $query);
$token_array = [];

foreach($result as $res){

    $stored_tokens_prev_month = $res->tokens_previous_month_user;
    $stored_total_tokens = $res->total_tokens_user;
    $tokens = [$stored_tokens_prev_month,$stored_total_tokens];

    $token_array[]= $tokens;

}

echo json_encode($token_array);
