<?php

include 'config.php';
require_once 'vendor/autoload.php';

session_start();

$databaseName = 'web_project_database';
$collectionName1 = 'users';
$collectionName2 = 'tokens';
$collectionName3 = 'Score';


// Connect to MongoDB
$client2 = new MongoDB\Client("mongodb://localhost:27017");

// Select the database and collection
$collection1 = $client2->$databaseName->$collectionName1;
$collection2 = $client2->$databaseName->$collectionName2;
$collection3 = $client2->$databaseName->$collectionName3;


date_default_timezone_set('Europe/Athens');
$first_day_this_month = date('01/m/Y'); // "01/01/2023"
$last_day_this_month  = date('t/m/Y');  // "31/01/2023"
$current_date = date("d/m/Y");          // "04/01/2023"

// Specify the query criteria
$query = array('type' => 'user');
// Count the number of matching documents
$count_users = $collection1->countDocuments($query);
//echo "Number of users:".$count_users;

$num_tokens_created = $count_users * 100;
$num_tokens_to_distribute_intermediate = ($num_tokens_created * 80)/100;
$num_tokens_to_distribute = round($num_tokens_to_distribute_intermediate/5);


if($current_date == $first_day_this_month){

    $update = [
        '$set' => [
            'tokens_created_for_this_month' => $num_tokens_created,
            'tokens_distribution_for_this_month' => $num_tokens_to_distribute,
//            'tokens_current_month_user' => 0
        ],
    ];

    // Update all documents in the collection
    $result_tok = $collection2->updateMany([], $update);

}elseif($current_date == $last_day_this_month){


    //Find the 5 usernames with the highest current_score in the 'score' collection
    $cursor = $collection3->find([], ['sort' => ['current_score' => -1], 'limit' => 5]);

    //Loop through the cursor and get the username of each document
    foreach ($cursor as $document) {

        $username = $document['username'];

        // Retrieve the current value of tokens_current_month_user
        $tokens_current_month_user = $collection2->findOne(
            ['username' => $username],
            ['projection' => ['tokens_current_month_user' => 1]]
        )['tokens_current_month_user'];

        // Update the tokens collection for the current user
        $collection2->updateOne(
            ['username' => $username],
            ['$set' => [
                'tokens_previous_month_user' => intval($tokens_current_month_user),
                'tokens_current_month_user' => $num_tokens_to_distribute
            ],'$inc' => [
                'total_tokens_user' => $num_tokens_to_distribute,
            ]]
        );

    }

}