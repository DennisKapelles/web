<?php

include 'config.php';
require_once 'vendor/autoload.php';

session_start();


if (isset($_POST["shop_name"]) && !empty($_POST["shop_name"])) {
    $shop_name = $_POST["shop_name"];
    // Connect to the database and retrieve the locations of the selected shop

    $databaseName = 'web_project_database';
    $collectionName = 'POIS';

    $client2 = new MongoDB\Client("mongodb://localhost:27017");
    $collection = $client2->$databaseName->$collectionName;

    $locations = array();

    // Use a regular expression to search for any shop names that contain the $shop_name string, ignoring the case
    //$query = array("name" => new MongoDB\BSON\Regex("/$shop_name/i"));
    //$query = array("name" => new MongoDB\BSON\Regex("/.*$shop_name.*/i"));
    //$query = array();
    //$query = array("name" => new MongoDB\BSON\Regex("/^$shop_name.*/i"));
    //$cursor = $collection->find($query);

    $filter = ['name' => ['$regex' => "^$shop_name"]];
    $cursor = $collection->find($filter);


    // Convert the results to an array and return it as a JSON response
    foreach ($cursor as $document) {
        $locations[] = $document;
    }
    echo json_encode($locations);
}
