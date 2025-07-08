<?php

include 'config.php';
require_once 'vendor/autoload.php';

session_start();

// Get the data from the POST request

$product = $_POST['product'];
$shop_name = $_POST['shop_name'];
$username = $_POST['username'];


$databaseName = 'web_project_database';
$collectionName = 'offers';
$collectionName2 = 'Score';
$collectionName3 = 'logs';

$cli = new MongoDB\Client('mongodb://localhost:27017');
$collection = $cli->$databaseName->$collectionName;

//Update field current_score of user that proposed the offer
$filt=['shop' => $shop_name, 'product_name' => $product, 'username' => $username];
$options=[];
$query = new MongoDB\Driver\Query($filt, $options);
$result = $client->executeQuery('web_project_database.offers', $query);

// Specify the query criteria
$filters = array('shop' => $shop_name, 'product_name' => $product, 'username' => $username);
// Count the number of matching documents
$count_users = $collection->countDocuments($filters);
//echo "Number of users:".$count_users;

if ($count_users == 0){
    $response_message = "Product name or username are wrong!";
}
else{
    foreach($result as $res){
        $user_name = $res->username;

        //Update field dislikes of product offer
//        $client1 = new MongoDB\Client('mongodb://localhost:27017');
//        $collection = $client1->$databaseName->$collectionName;
        $fi = ['$and' => [    ['shop' => $shop_name],
            ['product_name' => $product]]];
        $update_dislikes = ['$inc' => ['dislikes' => 1]];
        $insert_dislikes = $collection->updateOne($fi, $update_dislikes);

        $fil=['username' => $user_name];
        $opt=[];
        $query_score = new MongoDB\Driver\Query($fil, $opt);
        $result_score = $client->executeQuery('web_project_database.Score', $query_score);

        foreach ($result_score as $rs){
            $curr_score = $rs->current_score;
            if ($curr_score !== 0){
                $client2 = new MongoDB\Client('mongodb://localhost:27017');
                $collection = $client2->$databaseName->$collectionName2;
                $filter = ['username' => $user_name];
                $update_score = ['$inc' => ['current_score' => -1]];
                $insert_score = $collection->updateOne($filter, $update_score);
                $response_message = "Thank you! The proposer has lost 1 point.";
            }else{
                //If user's current_score is already 0
                $response_message = "Thank you! The proposer hasn't lost points.";
            }
        }
    }
    $collection2 = $cli->$databaseName->$collectionName3;
    //Update the "user_history" field of the document with the specified username
    $result_log = $collection2->updateOne(
        ['username' => $_SESSION['username']],
        ['$push' => ['user_history' => ['$each' => ['You disliked an offer for '.$product.' in the shop '.$shop_name], '$position' => 0]]]
    );


}
echo json_encode($response_message);