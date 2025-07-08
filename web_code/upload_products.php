<?php

include 'config.php';
require_once 'vendor/autoload.php';

session_start();

$databaseName = 'web_project_database';
$collectionName1 = 'prod and categ';
$collectionName2 = 'products';
$collectionName3 = 'categories';


// Connect to MongoDB
$client2 = new MongoDB\Client("mongodb://localhost:27017");

// Select the database and collection
$collection1 = $client2->$databaseName->$collectionName1;
$collection2 = $client2->$databaseName->$collectionName2;
$collection3 = $client2->$databaseName->$collectionName3;


$target_dir = "C:\\xampp\\htdocs\\web_last_dance\\";
$target_file = file_get_contents($target_dir . basename($_FILES["products"]["name"]));
//print_r($target_file);
$file = json_decode($target_file);
//print_r($file);


// Count the number of documents
$count_users = $collection1->countDocuments();
//echo "Number of users:".$count_users;

if ($count_users == 0){
    $bulkWrite = new MongoDB\Driver\BulkWrite;
    $bulkWrite->insert($file);
    $client->executeBulkWrite('web_project_database.prod and categ', $bulkWrite);
}else if ($count_users == 1){
    // Delete data from collection prices
    $bulk3 = new MongoDB\Driver\BulkWrite;
    $bulk3->delete([]);
    $manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');
    $result = $manager->executeBulkWrite('web_project_database.prod and categ', $bulk3);

    $bulkWrite = new MongoDB\Driver\BulkWrite;
    $bulkWrite->insert($file);
    $client->executeBulkWrite('web_project_database.prod and categ', $bulkWrite);
}

//                          -----Create Table products-----

 $filter=[];
 $options=[];
 $query = new MongoDB\Driver\Query($filter, $options);
 $result = $client->executeQuery('web_project_database.prod and categ', $query);

 foreach($result as $res)
 {
     $arrLength = count($res->products);
//   print_r($arrLength);
     for($i=0; $i<$arrLength; $i++){
         $id = $res->products[$i]->id;
         $name = $res->products[$i]->name;
         $category = $res->products[$i]->category;
         $subcategory = $res->products[$i]->subcategory;

         // Specify the query criteria
         $query = array('name' => $name);
         // Count the number of matching documents
         $count_users = $collection2->countDocuments($query);
//        echo "Number of users:".$count_users;
         if ($count_users == 0){
             $bulkWrite = new MongoDB\Driver\BulkWrite;
             $product = ["id" => $id,"name" => $name,"category" => $category,"subcategory" => $subcategory];
             $bulkWrite->insert($product);
             $client->executeBulkWrite('web_project_database.products', $bulkWrite);
         }
     }
 }

// ==============================================================================================

//                          -----Create Table categories-----

$filters=[];
$option=[];
$query = new MongoDB\Driver\Query($filters, $option);
$result = $client->executeQuery('web_project_database.prod and categ', $query);


foreach($result as $res)
{
    $arrLength = count($res->categories);
//    print_r($arrLength);
    for($i=0; $i<$arrLength; $i++){
        $id = $res->categories[$i]->id;
        $name = $res->categories[$i]->name;
        $subcategories = $res->categories[$i]->subcategories;

        // Specify the query criteria
        $query = array('name' => $name);
        // Count the number of matching documents
        $count_users = $collection3->countDocuments($query);
//        echo "Number of users:".$count_users;
        if ($count_users == 0){
            $bulkWrite = new MongoDB\Driver\BulkWrite;
            $product = ["id" => $id,"name" => $name,"subcategories" => $subcategories];
            $bulkWrite->insert($product);
            $client->executeBulkWrite('web_project_database.categories', $bulkWrite);
        }
    }
}

// Redirecting back
header("Location:".$_SERVER["HTTP_REFERER"]);