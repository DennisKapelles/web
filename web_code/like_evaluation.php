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

////Update field likes of product offer
//$client1 = new MongoDB\Client('mongodb://localhost:27017');
//$collection = $client->$databaseName->$collectionName;
//$fi = ['$and' => [    ['shop' => $shop_name],
//    ['product_name' => $product]]];
////$update_likes = ['$inc' => ['likes' => 1]];
////$insert_likes = $collection->updateOne($fi, $update_likes);

$cli = new MongoDB\Client('mongodb://localhost:27017');
$collection = $cli->$databaseName->$collectionName;


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

        //Update field likes of product offer
        $client1 = new MongoDB\Client('mongodb://localhost:27017');
        $collection = $client1->$databaseName->$collectionName;
        $fi = ['$and' => [    ['shop' => $shop_name],
            ['product_name' => $product]]];
        $update_likes = ['$inc' => ['likes' => 1]];
        $insert_likes = $collection->updateOne($fi, $update_likes);

        //Update field current_score of user that proposed the offer
        $client2 = new MongoDB\Client('mongodb://localhost:27017');
        $collection1 = $client2->$databaseName->$collectionName2;
        $filter = ['username' => $user_name];
        $update_score = ['$inc' => ['current_score' => 5]];
        $insert_score = $collection1->updateOne($filter, $update_score);
        $response_message = "Thank you! The proposer has received 5 points.";
    }

    $collection2 = $client1->$databaseName->$collectionName3;
    // Update the "user_history" field of the document with the specified username
    $result_log = $collection2->updateOne(
        ['username' => $_SESSION['username']],
        ['$push' => ['user_history' => ['$each' => ['You liked an offer for '.$product.' in the shop '.$shop_name], '$position' => 0]]]
    );

}

echo json_encode($response_message);