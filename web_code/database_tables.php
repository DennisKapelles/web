<?php

include 'config.php';

session_start();


// ==============================================================================================

//                          -----Create Table products-----

// $filter=[];
// $options=[];
// $query = new MongoDB\Driver\Query($filter, $options);
// $result = $client->executeQuery('web_project_database.prod and categ', $query);
//
//
//
// foreach($result as $res)
// {
//     for($i=0; $i<1270; $i++){
//         $id = $res->products[$i]->id;
//         $name = $res->products[$i]->name;
//         $category = $res->products[$i]->category;
//         $subcategory = $res->products[$i]->subcategory;
//
//         $bulkWrite = new MongoDB\Driver\BulkWrite;
//         $product = ["id" => $id,"name" => $name,"category" => $category,"subcategory" => $subcategory];
//         $bulkWrite->insert($product);
//         $client->executeBulkWrite('web_project_database.products', $bulkWrite);
//     };
//
// }
// ==============================================================================================

//                          -----Create Table categories-----

//$filter=[];
//$options=[];
//$query = new MongoDB\Driver\Query($filter, $options);
//$result = $client->executeQuery('web_project_database.prod and categ', $query);
//
//
//foreach($result as $res)
//{
//    for($i=0; $i<8; $i++){
//        $id = $res->categories[$i]->id;
//        $name = $res->categories[$i]->name;
//        $subcategories = $res->categories[$i]->subcategories;
//
//        $bulkWrite = new MongoDB\Driver\BulkWrite;
//        $product = ["id" => $id,"name" => $name,"subcategories" => $subcategories];
//        $bulkWrite->insert($product);
//        $client->executeBulkWrite('web_project_database.categories', $bulkWrite);
//    };
//
//}

// ==============================================================================================

//$filter=[];
//$options=[];
//$query = new MongoDB\Driver\Query($filter, $options);
//$result = $client->executeQuery('web_project_database.products', $query);
//
//
//foreach($result as $res)
//{
//    echo $res->name;
//    echo "<br>";
//}


//$filter=[];
//$options=[];
//$query = new MongoDB\Driver\Query($filter, $options);
//$result = $client->executeQuery('web_project_database.categories', $query);
//
//foreach($result as $res)
//{
//    for($i=0; $i<(count($res->subcategories, COUNT_RECURSIVE)); $i++){
//        echo $res->subcategories[$i]->name;
//        echo "<br>";
//    }
//    // echo $res->subcategories[0]->name;
//    echo "<br>";
//}

// ========================================================================================
//                          -----Pois-----

// $filter=[];
// $options=[];
// $query = new MongoDB\Driver\Query($filter, $options);
// $result = $client->executeQuery('web_project_database.pois', $query);
//
// foreach($result as $res){
//     for($i=0; $i<89; $i++){
//         $id = $res->features[$i]->id;
//         $properties = $res->features[$i]->properties;
//         $coordinates = $res->features[$i]->geometry->coordinates;
//
//         $bulkWrite = new MongoDB\Driver\BulkWrite;
//         $poi = ["id" => $id,"properties" => $properties,"coordinates" => $coordinates];
//         $bulkWrite->insert($poi);
//         $client->executeBulkWrite('web_project_database.points_of_interest', $bulkWrite);
//     }
// }

//// ========================================================================================
////                          -----Average Prices-----

//$filter=[];
//$options=[];
//$query = new MongoDB\Driver\Query($filter, $options);
//$resu = $client->executeQuery('web_project_database.prices', $query);
//
//$prices = [];
//foreach($resu as $res){
//    $prices = $res->data[0]->prices;
//}
//
//$fil=[];
//$opt=[];
//$query = new MongoDB\Driver\Query($fil, $opt);
//$result = $client->executeQuery('web_project_database.products', $query);
//
//foreach($result as $res){
//    $name = $res->name;
//    $prices_array = $prices;
//
//    $bulkWrite = new MongoDB\Driver\BulkWrite;
//    $pri = ["name" => $name,"prices" => $prices_array];
//    $bulkWrite->insert($pri);
//    $client->executeBulkWrite('web_project_database.average_prices', $bulkWrite);
//}



// ========================================================================================
//                          -----Days Before Current Day-----

//// current time in PHP
//$datetime = date("Y-m-d ");
//// print current time
//echo $datetime;
//echo "<br>";
//echo "<br>";
////After using of strotime fuction then result
//$b6d = date("d-m-Y", strtotime("-6 days"));
//echo $b6d;
//echo "<br>";
//$b5d = date("d-m-Y", strtotime("-5 days"));
//echo $b5d;
//echo "<br>";
//$b4d = date("d-m-Y", strtotime("-4 days"));
//echo $b4d;
//echo "<br>";
//$b3d = date("d-m-Y", strtotime("-3 days"));
//echo $b3d;
//echo "<br>";
//$b2d = date("d-m-Y", strtotime("-2 days"));
//echo $b2d;

//$mongo->average_prices->update(array('prices.date' => 2022-11-24), array('prices.$.date' => 2022-11-30));

// ========================================================================================
//                          -----Average Prices NEW-----

//$fil=[];
//$opt=[];
//$query = new MongoDB\Driver\Query($fil, $opt);
//$result = $client->executeQuery('web_project_database.average_prices', $query);
//
//$b7d = date("d-m-Y", strtotime("-7 days"));
//$b6d = date("d-m-Y", strtotime("-6 days"));
//$b5d = date("d-m-Y", strtotime("-5 days"));
//$b4d = date("d-m-Y", strtotime("-4 days"));
//$b3d = date("d-m-Y", strtotime("-3 days"));
//$b2d = date("d-m-Y", strtotime("-2 days"));
//$b1d = date("d-m-Y", strtotime("-1 day"));
//
//foreach($result as $res){
//    $name = $res->name;
//    $price_b7d = rand(1,55)/10;
//    $price_b6d = rand(1,55)/10;
//    $price_b5d = rand(1,55)/10;
//    $price_b4d = rand(1,55)/10;
//    $price_b3d = rand(1,55)/10;
//    $price_b2d = rand(1,55)/10;
//    $price_b1d = rand(1,55)/10;
//
//    $bulkWrite = new MongoDB\Driver\BulkWrite;
//    $pri = ["name"=>$name,"Date_7_days_before" => $b7d,"Price_7_days_before" => $price_b7d,"Date_6_days_before" => $b6d,"Price_6_days_before" => $price_b6d,"Date_5_days_before" => $b5d,"Price_5_days_before" => $price_b5d,"Date_4_days_before" => $b4d,"Price_4_days_before" => $price_b4d,"Date_3_days_before" => $b3d,"Price_3_days_before" => $price_b3d,"Date_2_days_before" => $b2d,"Price_2_days_before" => $price_b2d,"Date_1_day_before" => $b1d,"Price_1_day_before" => $price_b1d];
//    $bulkWrite->insert($pri);
//    $client->executeBulkWrite('web_project_database.Avg_prices', $bulkWrite);
//
//}

// ========================================================================================
//                          -----Filter with _id-----

//$filter=["_id"=>new MongoDB\BSON\ObjectId('638ccedd0c1c0000db00af95')];
//$options=[];
//$query = new MongoDB\Driver\Query($filter, $options);
//$result = $client->executeQuery('web_project_database.average_prices', $query);
//
//$prices = [];
//foreach($result as $res){
//    echo $res->name;
//}


//// ========================================================================================
////                          -----Pois NEW-----
//
// $filter=[];
// $options=[];
// $query = new MongoDB\Driver\Query($filter, $options);
// $result = $client->executeQuery('web_project_database.pois_2', $query);
//
// foreach($result as $res){
//     for($i=0; $i<89; $i++){
//         $id = $res->features[$i]->id;
//         $name = $res->features[$i]->properties->name;
//         $coordinates = $res->features[$i]->geometry->coordinates;
//
////         echo $i."  ".$id." ".$name;
////         echo "<br>";
//         $bulkWrite = new MongoDB\Driver\BulkWrite;
//         $poi = ["id" => $id,"name" => $name,"coordinates" => $coordinates];
//         $bulkWrite->insert($poi);
//         $client->executeBulkWrite('web_project_database.POIS', $bulkWrite);
//     }
// }
//// ========================================================================================
?>


<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="styleuser.css">
    <title>Database Configuration</title>

</head>
<body>

</body>
</html>
