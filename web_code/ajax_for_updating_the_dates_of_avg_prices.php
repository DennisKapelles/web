<?php

include 'config.php';
require_once 'vendor/autoload.php';

session_start();

$databaseName = 'web_project_database';
$collectionName = 'Avg_prices';

// Connect to MongoDB
$client2 = new MongoDB\Client("mongodb://localhost:27017");

// Select the database and collection
$collection = $client2->$databaseName->$collectionName;
date_default_timezone_set('Europe/Athens');
$b7d = date("d/m/Y", strtotime("-7 days"));
$b6d = date("d/m/Y", strtotime("-6 days"));
$b5d = date("d/m/Y", strtotime("-5 days"));
$b4d = date("d/m/Y", strtotime("-4 days"));
$b3d = date("d/m/Y", strtotime("-3 days"));
$b2d = date("d/m/Y", strtotime("-2 days"));
$b1d = date("d/m/Y", strtotime("-1 day"));

// Create the update object
$update = [
    '$set' => [
        'Date_7_days_before' => $b7d,
        'Date_6_days_before' => $b6d,
        'Date_5_days_before' => $b5d,
        'Date_4_days_before' => $b4d,
        'Date_3_days_before' => $b3d,
        'Date_2_days_before' => $b2d,
        'Date_1_day_before' => $b1d,
    ],
];

// Update all documents in the collection
$result = $collection->updateMany([], $update);
