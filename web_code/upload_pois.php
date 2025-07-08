<?php

include 'config.php';
require_once 'vendor/autoload.php';

session_start();

$databaseName = 'web_project_database';
$collectionName1 = 'pois_2';
$collectionName2 = 'POIS';


// Connect to MongoDB
$client2 = new MongoDB\Client("mongodb://localhost:27017");

// Select the database and collection
$collection1 = $client2->$databaseName->$collectionName1;
$collection2 = $client2->$databaseName->$collectionName2;


//echo 'file-chosen';
//print_r($_FILES);
$target_dir = "C:\\xampp\\htdocs\\web_last_dance\\";
$target_file = file_get_contents($target_dir . basename($_FILES["fileToUpload"]["name"]));
//print_r($target_file);
$file = json_decode($target_file);
//print_r($file);

// Count the number of documents
$count_users = $collection1->countDocuments();
//echo "Number of users:".$count_users;

if ($count_users == 0){
    $bulkWrite = new MongoDB\Driver\BulkWrite;
    $bulkWrite->insert($file);
    $client->executeBulkWrite('web_project_database.pois_2', $bulkWrite);
}else if ($count_users == 1){
    // Delete data from collection pois_2
    $bulk = new MongoDB\Driver\BulkWrite;
    $bulk->delete([]);
    $manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');
    $result = $manager->executeBulkWrite('web_project_database.pois_2', $bulk);

    $bulkWrite = new MongoDB\Driver\BulkWrite;
    $bulkWrite->insert($file);
    $client->executeBulkWrite('web_project_database.pois_2', $bulkWrite);
}

// ========================================================================================
//                          -----Pois NEW-----

$filter=[];
$options=[];
$query = new MongoDB\Driver\Query($filter, $options);
$result = $client->executeQuery('web_project_database.pois_2', $query);

foreach($result as $res){
    $arrLength = count($res->features);
//    print_r($arrLength);
    for($i=0; $i<$arrLength; $i++){
        $id = $res->features[$i]->id;
        $name = $res->features[$i]->properties->name;
        $coordinates = $res->features[$i]->geometry->coordinates;

        // Specify the query criteria
        $query = array('name' => $name);
        // Count the number of matching documents
        $count_users = $collection2->countDocuments($query);
//        echo "Number of users:".$count_users;
        if ($count_users == 0) {
            $bulkWrite = new MongoDB\Driver\BulkWrite;
            $poi = ["id" => $id, "name" => $name, "coordinates" => $coordinates];
            $bulkWrite->insert($poi);
            $client->executeBulkWrite('web_project_database.POIS', $bulkWrite);
        }
    }
}
// ========================================================================================

// Redirecting back
header("Location:".$_SERVER["HTTP_REFERER"]);