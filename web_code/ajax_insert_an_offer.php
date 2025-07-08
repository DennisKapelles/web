<?php

include 'config.php';
require_once 'vendor/autoload.php';

session_start();

// Get the data from the POST request

$product = $_POST['product'];
$price = floatval($_POST['price']);
//$price = $_POST['price'];
$shop_name = $_POST['shop_name'];

$databaseName = 'web_project_database';
$collectionName = 'offers';
$collectionName2 = 'Score';
$collectionName3 = 'logs';
$collectionName4 = 'all_offers';

date_default_timezone_set('Europe/Athens');
// ΠΑΙΡΝΟΥΜΕ ΤΗ ΜΕΣΗ ΤΙΜΗ ΤΗΣ ΠΡΟΗΓΟΥΜΕΝΗΣ ΜΕΡΑΣ ΤΟΥ ΠΡΟΙΟΝΤΟΣ ΠΟΥ ΥΠΟΒΑΛΛΕΙ Ο ΧΡΗΣΤΗΣ ΓΙΑ ΝΑ ΤΗ ΣΥΓΚΡΙΝΟΥΜΕ ΜΕ ΤΗ ΝΕΑ
$f=['name'=>$product];
$op=[];
$q = new MongoDB\Driver\Query($f, $op);
$res_price_bef_1_day = $client->executeQuery('web_project_database.Avg_prices', $q);
$price_one_day_before = 0;

foreach($res_price_bef_1_day as $res_pb1d)
{
    $price_one_day_before = $res_pb1d->Price_1_day_before;
}

// ΠΑΙΡΝΟΥΜΕ ΤΟ ΜΕΣΟ ΟΡΟ ΟΛΩΝ ΤΩΝ ΜΕΣΩΝ ΤΙΜΩΝ ΤΩΝ ΠΡΟΗΓΟΥΜΕΝΩΝ 7 ΗΜΕΡΩΝ (ΜΙΑ ΕΒΔΟΜΑΔΑ(ΠΑΡΑΔΟΧΗ)) ΤΟΥ ΠΡΟΙΟΝΤΟΣ
// ΠΟΥ ΥΠΟΒΑΛΛΕΙ Ο ΧΡΗΣΤΗΣ ΓΙΑ ΝΑ ΤΟΝ ΣΥΓΚΡΙΝΟΥΜΕ ΜΕ ΤΗ ΝΕΑ ΤΙΜΗ ΤΟΥ ΧΡΗΣΤΗ
$filter_prod=['name'=>$product];
$op_prod=[];
$query_avg = new MongoDB\Driver\Query($filter_prod, $op_prod);
$res_avg_price_for_7_days = $client->executeQuery('web_project_database.Avg_prices', $query_avg);
$avg_price_for_the_past_7_days = 0;

foreach($res_avg_price_for_7_days as $res_avgpf7d)
{
    $pb1d = $res_avgpf7d->Price_1_day_before;
    $pb2d =$res_avgpf7d->Price_2_days_before;
    $pb3d =$res_avgpf7d->Price_3_days_before;
    $pb4d =$res_avgpf7d->Price_4_days_before;
    $pb5d =$res_avgpf7d->Price_5_days_before;
    $pb6d =$res_avgpf7d->Price_6_days_before;
    $pb7d =$res_avgpf7d->Price_7_days_before;

    $avg_price_for_the_past_7_days = ($pb1d + $pb2d + $pb3d + $pb4d + $pb5d + $pb6d + $pb7d)/7;
}

// ΥΠΟΛΟΓΙΣΜΟΣ ΤΩΝ ΑΠΑΡΑΙΤΗΤΩΝ ΤΙΜΩΝ (20% ΤΙΜΗΣ ΠΡΟΗΓΟΥΜΕΝΗΣ ΜΕΡΑΣ (Α) , 20% ΤΙΜΗΣ ΠΡΟΗΓΟΥΜΕΝΩΝ 7 ΗΜΕΡΩΝ (Β))
$li = ($price_one_day_before * 20)/100;
$pr_li = $price_one_day_before - $li;

$lim = ($avg_price_for_the_past_7_days * 20)/100;
$pr_lim = $avg_price_for_the_past_7_days - $lim;


// ΒΑΖΟΥΜΕ ΣΕ ΠΙΝΑΚΑ ΟΛΑ ΤΑ ΠΡΟΙΟΝΤΑ ΓΙΑ ΤΑ ΟΠΟΙΑ Ο ΧΡΗΣΤΗΣ ΕΧΕΙ ΥΠΟΒΑΛΛΕΙ ΠΡΟΣΦΟΡΑ ΣΤΟ ΙΔΙΟ ΚΑΤΑΣΤΗΜΑ
$filter=["shop" => $_POST['shop_name'],"username"=> $_SESSION['username']];
$options=[];
$query = new MongoDB\Driver\Query($filter, $options);
$result = $client->executeQuery('web_project_database.offers', $query);
$products = array("");
$response_mes = "null";

foreach($result as $res)
{
    $stored_product_name = $res->product_name;
    $products[] = $stored_product_name;
}

// ΠΑΙΡΝΟΥΜΕ ΤΗΝ ΠΡΟΗΓΟΥΜΕΝΗ ΤΙΜΗ ΠΟΥ ΥΠΕΒΑΛΕ Ο ΧΡΗΣΤΗΣ ΓΙΑ ΤΟ ΙΔΙΟ ΠΡΟΙΟΝ ΣΤΟ ΙΔΙΟ ΜΑΓΑΖΙ ΑΝ ΥΠΑΡΧΕΙ
$fil=['product_name'=>$product, "shop" => $shop_name,"username"=> $_SESSION['username']];
$opt=[];
$quer = new MongoDB\Driver\Query($fil, $opt);
$resu = $client->executeQuery('web_project_database.offers', $query);
$price_limit_for_prev_price = 0;

foreach($resu as $r) {

    $stored_price = $r->price;
    $limit = ($stored_price * 20) / 100;
    $price_limit_for_prev_price = $stored_price - $limit;

}

// ΑΝ ΠΑΕΙ ΝΑ ΒΑΛΕΙ ΠΡΟΣΦΟΡΑ ΣΤΟ ΙΔΙΟ ΜΑΓΑΖΙ ΓΙΑ ΤΟ ΙΔΙΟ ΠΡΟΙΟΝ ΓΙΝΕΤΑΙ ΕΛΕΓΧΟΣ ΓΙΑ ΤΗ ΝΕΑ ΤΙΜΗ ΠΟΥ ΥΠΟΒΑΛΛΕΙ
if (in_array($product, $products))
{
    // ΑΝ Η ΝΕΑ ΤΙΜΗ ΕΙΝΑΙ ΜΙΚΡΟΤΕΡΗ ΑΠΟ ΤΟ 20% ΤΗΣ ΑΡΧΙΚΗΣ ΓΙΝΕΤΑΙ ΕΝΗΜΕΡΩΣΗ ΤΗΣ ΤΙΜΗΣ ΑΛΛΙΩΣ ΟΧΙ
    if ($price < $price_limit_for_prev_price){

        $client2 = new MongoDB\Client('mongodb://localhost:27017');
        $collection = $client2->$databaseName->$collectionName;
        $fi = ['$and' => [    ['shop' => $shop_name],
            ['product_name' => $product],
            ['username' => $_SESSION['username']]
        ]];
        $update = ['$set' => ['price' => $price]];
        $insert_res = $collection->updateOne($fi, $update);
        //$response_mes = "Successful update of the price";


        $collection2 = $client2->$databaseName->$collectionName3;

        $filter = [
            'product_name' => $product,
            'shop' => $shop_name,
            'username' => $_SESSION['username']
        ];

        //$collection = $client2->$databaseName->$collectionName;
        $document = $collection->findOne($filter,[]);
        if ($document) {
            $offer_date_for_log = $document->offer_date;
            // Update the "user_history" field of the document with the specified username
            $result_log = $collection2->updateOne(
                ['username' => $_SESSION['username']],
                ['$push' => ['user_history' => ['$each' => ['You updated an offer for '.$product. ' with new price '.$price.' in the shop '.$shop_name. ' in the date '.$offer_date_for_log], '$position' => 0]]]
            );
        }

        //ΑΝ ΝΕΑ ΤΙΜΗ ΜΙΚΡΟΤΕΡΗ ΚΑΙ ΑΠΟ Α ΚΑΙ ΑΠΟ Β Ο ΧΡΗΣΤΗΣ ΛΑΜΒΑΝΕΙ 70 ΠΟΝΤΟΥΣ
        if($price < $pr_li && $price < $pr_lim){

            $client2 = new MongoDB\Client('mongodb://localhost:27017');
            $collection = $client2->$databaseName->$collectionName2;
            $filter_for_score = ['username' => $_SESSION['username']];
            $update_score = ['$inc' => ['current_score' => 70]];
            $insert_score = $collection->updateOne($filter_for_score, $update_score);
            $response_mes = "Successful update of the price.You have received 70 points.";

            $client2 = new MongoDB\Client('mongodb://localhost:27017');
            $collection = $client2->$databaseName->$collectionName;
            $filter_for_criteria = ['$and' => [['shop' => $shop_name],
                ['product_name' => $product],
                ['username' => $_SESSION['username']]
            ]];
            $update_criteria = ['$set' => ['criteria' => 'yes']];
            $insert_criteria = $collection->updateOne($filter_for_criteria, $update_criteria);

        }
        //ΑΝ ΝΕΑ ΤΙΜΗ ΜΙΚΡΟΤΕΡΗ ΑΠΟ Α ΑΛΛΑ ΟΧΙ ΑΠΟ Β Ο ΧΡΗΣΤΗΣ ΛΑΜΒΑΝΕΙ 50 ΠΟΝΤΟΥΣ
        elseif($price < $pr_li && $price > $pr_lim){

            $client2 = new MongoDB\Client('mongodb://localhost:27017');
            $collection = $client2->$databaseName->$collectionName2;
            $filter_for_score = ['username' => $_SESSION['username']];
            $update_score = ['$inc' => ['current_score' => 50]];
            $insert_score = $collection->updateOne($filter_for_score, $update_score);
            $response_mes = "Successful update of the price.You have received 50 points.";

            $client2 = new MongoDB\Client('mongodb://localhost:27017');
            $collection = $client2->$databaseName->$collectionName;
            $filter_for_criteria = ['$and' => [['shop' => $shop_name],
                ['product_name' => $product],
                ['username' => $_SESSION['username']]
            ]];
            $update_criteria = ['$set' => ['criteria' => 'yes']];
            $insert_criteria = $collection->updateOne($filter_for_criteria, $update_criteria);

        }
        //ΑΝ ΝΕΑ ΤΙΜΗ ΜΙΚΡΟΤΕΡΗ ΑΠΟ Β ΑΛΛΑ ΟΧΙ ΑΠΟ Α Ο ΧΡΗΣΤΗΣ ΛΑΜΒΑΝΕΙ 20 ΠΟΝΤΟΥΣ
        elseif($price > $pr_li && $price < $pr_lim){

            $client2 = new MongoDB\Client('mongodb://localhost:27017');
            $collection = $client2->$databaseName->$collectionName2;
            $filter_for_score = ['username' => $_SESSION['username']];
            $update_score = ['$inc' => ['current_score' => 20]];
            $insert_score = $collection->updateOne($filter_for_score, $update_score);
            $response_mes = "Successful update of the price.You have received 20 points.";

            $client2 = new MongoDB\Client('mongodb://localhost:27017');
            $collection = $client2->$databaseName->$collectionName;
            $filter_for_criteria = ['$and' => [['shop' => $shop_name],
                ['product_name' => $product],
                ['username' => $_SESSION['username']]
            ]];
            $update_criteria = ['$set' => ['criteria' => 'yes']];
            $insert_criteria = $collection->updateOne($filter_for_criteria, $update_criteria);

        }
        //ΑΛΛΙΩΣ Ο ΧΡΗΣΤΗΣ ΔΕ ΛΑΜΒΑΝΕΙ ΠΟΝΤΟΥΣ
        else{
            $response_mes = "Successful update of the price.You did not have received any points.";
        }

    }
    else{
        $response_mes = "You cannot make this addition because the appropriate conditions are not met.(In the same store for the same product, the new price entered by the same user must be less than 20% of the original price of the product).";
    }
}
// ΑΝ ΠΑΕΙ ΝΑ ΒΑΛΕΙ ΠΡΟΣΦΟΡΑ ΣΤΟ ΙΔΙΟ ΜΑΓΑΖΙ ΓΙΑ ΔΙΑΦΟΡΕΤΙΚΟ ΠΡΟΙΟΝ ΤΟΤΕ ΑΠΛΑ ΓΙΝΕΤΑΙ Η ΕΓΓΡΑΦΗ
else{

    $bulkWrite = new MongoDB\Driver\BulkWrite;
    $doc = ['product_name' => $product, 'shop' => $shop_name, 'username' => $_SESSION['username'], 'price' => $price, 'likes' => 0, 'dislikes' => 0, 'stock' => 'yes', 'offer_date' => date("d/m/Y"), 'expiry_date' => date("d/m/Y", strtotime("+7 days"))];
    $bulkWrite->insert($doc);
    $client->executeBulkWrite('web_project_database.all_offers', $bulkWrite);


//    $client2 = new MongoDB\Client('mongodb://localhost:27017');
//    $collection2 = $client2->$databaseName->$collectionName3;
//
//    $filter = [
//        'product_name' => $product,
//        'shop' => $shop_name,
//        'username' => $_SESSION['username']
//    ];
//
//    $collection = $client2->$databaseName->$collectionName;
//    $document = $collection->findOne($filter,[]);
//    if ($document) {
//        $offer_date_for_log = $document->offer_date;
//        // Update the "user_history" field of the document with the specified username
//        $result_log = $collection2->updateOne(
//            ['username' => $_SESSION['username']],
//            ['$push' => ['user_history' => ['$each' => ['You added a new offer for '.$product. ' with price '.$price.' in the shop '.$shop_name. ' in the date '.$offer_date_for_log], '$position' => 0]]]
//        );
//    }

    //ΑΝ ΝΕΑ ΤΙΜΗ ΜΙΚΡΟΤΕΡΗ ΚΑΙ ΑΠΟ Α ΚΑΙ ΑΠΟ Β Ο ΧΡΗΣΤΗΣ ΛΑΜΒΑΝΕΙ 70 ΠΟΝΤΟΥΣ
    if($price < $pr_li && $price < $pr_lim){

        $client2 = new MongoDB\Client('mongodb://localhost:27017');
        $collection = $client2->$databaseName->$collectionName2;
        $filter_for_score = ['username' => $_SESSION['username']];
        $update_score = ['$inc' => ['current_score' => 70]];
        $insert_score = $collection->updateOne($filter_for_score, $update_score);
        $response_mes = "Successful insertion of offer.You have received 70 points.";

        $bulkWrite = new MongoDB\Driver\BulkWrite;
        $doc = ['product_name' => $product, 'shop' => $shop_name, 'username' => $_SESSION['username'], 'price' => $price, 'likes' => 0, 'dislikes' => 0, 'stock' => 'yes', 'offer_date' => date("d/m/Y"), 'expiry_date' => date("d/m/Y", strtotime("+7 days")), 'criteria' => 'yes'];
        $bulkWrite->insert($doc);
        $client->executeBulkWrite('web_project_database.offers', $bulkWrite);

    }
    //ΑΝ ΝΕΑ ΤΙΜΗ ΜΙΚΡΟΤΕΡΗ ΑΠΟ Α ΑΛΛΑ ΟΧΙ ΑΠΟ Β Ο ΧΡΗΣΤΗΣ ΛΑΜΒΑΝΕΙ 50 ΠΟΝΤΟΥΣ
    elseif($price < $pr_li && $price > $pr_lim){

        $client2 = new MongoDB\Client('mongodb://localhost:27017');
        $collection = $client2->$databaseName->$collectionName2;
        $filter_for_score = ['username' => $_SESSION['username']];
        $update_score = ['$inc' => ['current_score' => 50]];
        $insert_score = $collection->updateOne($filter_for_score, $update_score);
        $response_mes = "Successful insertion of offer.You have received 50 points.";

        $bulkWrite = new MongoDB\Driver\BulkWrite;
        $doc = ['product_name' => $product, 'shop' => $shop_name, 'username' => $_SESSION['username'], 'price' => $price, 'likes' => 0, 'dislikes' => 0, 'stock' => 'yes', 'offer_date' => date("d/m/Y"), 'expiry_date' => date("d/m/Y", strtotime("+7 days")), 'criteria' => 'yes'];
        $bulkWrite->insert($doc);
        $client->executeBulkWrite('web_project_database.offers', $bulkWrite);

    }
    //ΑΝ ΝΕΑ ΤΙΜΗ ΜΙΚΡΟΤΕΡΗ ΑΠΟ Β ΑΛΛΑ ΟΧΙ ΑΠΟ Α Ο ΧΡΗΣΤΗΣ ΛΑΜΒΑΝΕΙ 20 ΠΟΝΤΟΥΣ
    elseif($price > $pr_li && $price < $pr_lim){

        $client2 = new MongoDB\Client('mongodb://localhost:27017');
        $collection = $client2->$databaseName->$collectionName2;
        $filter_for_score = ['username' => $_SESSION['username']];
        $update_score = ['$inc' => ['current_score' => 20]];
        $insert_score = $collection->updateOne($filter_for_score, $update_score);
        $response_mes = "Successful insertion of offer.You have received 20 points.";

        $bulkWrite = new MongoDB\Driver\BulkWrite;
        $doc = ['product_name' => $product, 'shop' => $shop_name, 'username' => $_SESSION['username'], 'price' => $price, 'likes' => 0, 'dislikes' => 0, 'stock' => 'yes', 'offer_date' => date("d/m/Y"), 'expiry_date' => date("d/m/Y", strtotime("+7 days")), 'criteria' => 'yes'];
        $bulkWrite->insert($doc);
        $client->executeBulkWrite('web_project_database.offers', $bulkWrite);

    }
    //ΑΛΛΙΩΣ Ο ΧΡΗΣΤΗΣ ΔΕ ΛΑΜΒΑΝΕΙ ΠΟΝΤΟΥΣ
    else{

        $bulkWrite = new MongoDB\Driver\BulkWrite;
        $doc = ['product_name' => $product, 'shop' => $shop_name, 'username' => $_SESSION['username'], 'price' => $price, 'likes' => 0, 'dislikes' => 0, 'stock' => 'yes', 'offer_date' => date("d/m/Y"), 'expiry_date' => date("d/m/Y", strtotime("+7 days")), 'criteria' => 'no'];
        $bulkWrite->insert($doc);
        $client->executeBulkWrite('web_project_database.offers', $bulkWrite);

        $response_mes = "Successful insertion of offer.You did not have received any points.";
    }

    $client2 = new MongoDB\Client('mongodb://localhost:27017');
    $collection2 = $client2->$databaseName->$collectionName3;

    $filter = [
        'product_name' => $product,
        'shop' => $shop_name,
        'username' => $_SESSION['username']
    ];

    $collection = $client2->$databaseName->$collectionName;
    $document = $collection->findOne($filter,[]);
    if ($document) {
        $offer_date_for_log = $document->offer_date;
        // Update the "user_history" field of the document with the specified username
        $result_log = $collection2->updateOne(
            ['username' => $_SESSION['username']],
            ['$push' => ['user_history' => ['$each' => ['You added a new offer for '.$product. ' with price '.$price.' in the shop '.$shop_name. ' in the date '.$offer_date_for_log], '$position' => 0]]]
        );
    }

}

echo json_encode($response_mes);