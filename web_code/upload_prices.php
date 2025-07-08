<?php

include 'config.php';
require_once 'vendor/autoload.php';

session_start();

$databaseName = 'web_project_database';
$collectionName1 = 'prices';
$collectionName2 = 'Avg_prices';

date_default_timezone_set('Europe/Athens');


// Connect to MongoDB
$client2 = new MongoDB\Client("mongodb://localhost:27017");

// Select the database and collection
$collection1 = $client2->$databaseName->$collectionName1;
$collection2 = $client2->$databaseName->$collectionName2;


//print_r($_FILES);
$target_dir = "C:\\xampp\\htdocs\\web_last_dance\\";
$target_file = file_get_contents($target_dir . basename($_FILES["prices"]["name"]));
//print_r($target_file);
$file = json_decode($target_file);
//print_r($file);


// Count the number of documents
$count_users = $collection1->countDocuments();
//echo "Number of users:".$count_users;

if ($count_users == 0){
    $bulkWrite = new MongoDB\Driver\BulkWrite;
    $bulkWrite->insert($file);
    $client->executeBulkWrite('web_project_database.prices', $bulkWrite);
}else if ($count_users == 1){
    // Delete data from collection prices
    $bulk3 = new MongoDB\Driver\BulkWrite;
    $bulk3->delete([]);
    $manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');
    $result = $manager->executeBulkWrite('web_project_database.prices', $bulk3);

    $bulkWrite = new MongoDB\Driver\BulkWrite;
    $bulkWrite->insert($file);
    $client->executeBulkWrite('web_project_database.prices', $bulkWrite);
}




// ========================================================================================
//                          -----Average Prices-----
//
$fil=[];
$opt=[];
$query = new MongoDB\Driver\Query($fil, $opt);
$result = $client->executeQuery('web_project_database.prices', $query);

$b7d = date("d/m/Y", strtotime("-7 days"));
$b6d = date("d/m/Y", strtotime("-6 days"));
$b5d = date("d/m/Y", strtotime("-5 days"));
$b4d = date("d/m/Y", strtotime("-4 days"));
$b3d = date("d/m/Y", strtotime("-3 days"));
$b2d = date("d/m/Y", strtotime("-2 days"));
$b1d = date("d/m/Y", strtotime("-1 day"));



foreach($result as $res) {
    $arrLength = count($res->data);
//    print_r($arrLength);
    for ($i = 0; $i < $arrLength; $i++) {
        $name = $res->data[$i]->name;
        $price_b7d = $res->data[$i]->Price_7_days_before;
        $price_b6d = $res->data[$i]->Price_6_days_before;
        $price_b5d = $res->data[$i]->Price_5_days_before;
        $price_b4d = $res->data[$i]->Price_4_days_before;
        $price_b3d = $res->data[$i]->Price_3_days_before;
        $price_b2d = $res->data[$i]->Price_2_days_before;
        $price_b1d = $res->data[$i]->Price_1_day_before;

        // Specify the query criteria
        $query = array('name' => $name);
        // Count the number of matching documents
        $count_users = $collection2->countDocuments($query);
//        echo "Number of users:".$count_users;
        if ($count_users == 0){
            $bulkWrite = new MongoDB\Driver\BulkWrite;
            $pri = ["name"=>$name,"Date_7_days_before" => $b7d,"Price_7_days_before" => $price_b7d,"Date_6_days_before" => $b6d,"Price_6_days_before" => $price_b6d,"Date_5_days_before" => $b5d,"Price_5_days_before" => $price_b5d,"Date_4_days_before" => $b4d,"Price_4_days_before" => $price_b4d,"Date_3_days_before" => $b3d,"Price_3_days_before" => $price_b3d,"Date_2_days_before" => $b2d,"Price_2_days_before" => $price_b2d,"Date_1_day_before" => $b1d,"Price_1_day_before" => $price_b1d];
            $bulkWrite->insert($pri);
            $client->executeBulkWrite('web_project_database.Avg_prices', $bulkWrite);
        }else if ($count_users == 1){
            // Create the update object
            $update = [
                '$set' => [
                    'Price_7_days_before' => $price_b7d,
                    'Price_6_days_before' => $price_b6d,
                    'Price_5_days_before' => $price_b5d,
                    'Price_4_days_before' => $price_b4d,
                    'Price_3_days_before' => $price_b3d,
                    'Price_2_days_before' => $price_b2d,
                    'Price_1_day_before' => $price_b1d,
                ],
            ];

            // Update specific document in the collection
            $result = $collection2->updateOne(["name" => $name], $update);
        }
    }
}


// Redirecting back
header("Location:".$_SERVER["HTTP_REFERER"]);
