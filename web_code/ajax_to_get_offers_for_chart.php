<?php

include 'config.php';
require_once 'vendor/autoload.php';

session_start();

//// Retrieve the year and month from the request
//$year = (int)$_POST['year'];
//$month = (int)$_POST['month'];

// Get the year and month from the request data
$year = $_POST['year'];
$month = $_POST['month'];

$databaseName = 'web_project_database';
$collectionName = 'all_offers';

$client2 = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client2->$databaseName->$collectionName;

// Get the number of days in the month
$date = new DateTime("${year}-${month}-01");
$date->modify('+1 month');
$date->modify('-1 day');
$days_in_month = $date->format('d');

// Initialize an array to hold the data
$data = [
    'labels' => range(1, $days_in_month),
    'data' => array_fill(1, $days_in_month, 0)
];

// Create the filter
$filter = [
    'offer_date' => [
        '$regex' => "^.*\/${month}\/${year}$"
    ]
];

// Retrieve the offers from the collection
$offers = $collection->find($filter);

// Loop over the offers and add the data to the $data array
foreach ($offers as $offer) {
    $day = (int) explode('/', $offer['offer_date'])[0];
    $data['data'][$day]++;
}

// Return the data as JSON
echo json_encode($data);
