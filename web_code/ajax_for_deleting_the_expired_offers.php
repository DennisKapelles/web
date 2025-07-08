<?php

include 'config.php';

require_once 'vendor/autoload.php';

session_start();
error_reporting(E_ALL);

date_default_timezone_set('Europe/Athens');//--------------------------

$databaseName = 'web_project_database';
$collectionName = 'offers';
$current_date = date("d/m/Y");
$current_timestamp = date($current_date);

$fil=[];
$opt=[];
$query = new MongoDB\Driver\Query($fil, $opt);
$result = $client->executeQuery('web_project_database.offers', $query);

foreach($result as $res1){
    $stored_expiry_date = $res1->expiry_date;
    $stored_product = $res1->product_name;
    $stored_shop = $res1->shop;
    $stored_username = $res1->username;
    $stored_offer_price = $res1->price;

    //====================Αν έχει περάσει η ημερομηνία λήξης============================
    $expiry_date = DateTime::createFromFormat('d/m/Y', $stored_expiry_date);
    $current_date2 = DateTime::createFromFormat('d/m/Y', $current_date);
    if ($expiry_date < $current_date2) {
        //echo "expired";
        $client2 = new MongoDB\Client("mongodb://localhost:27017");
        $collection = $client2->$databaseName->$collectionName;
        $delete_res = $collection->deleteOne([
            'expiry_date' => $stored_expiry_date,
            'product_name' => $stored_product,
            'shop' => $stored_shop,
            'username' => $stored_username,
        ]);
    }
    //================================================

    // ΑΝ Η ΗΜΕΡΟΜΗΝΙΑ ΛΗΞΗΣ ΙΣΟΥΤΑΙ ΜΕ ΤΗΝ ΤΩΡΙΝΗ ΗΜΕΡΟΜΗΝΙΑ ΤΗΣ ΠΡΟΣΦΟΡΑΣ ΓΙΝΕΤΑΙ ΕΛΕΓΧΟΣ ΓΙΑ ΤΙΣ ΤΙΜΕΣ
    if($stored_expiry_date == $current_timestamp){

        //ΕΛΕΓΧΟΣ ΓΙΑ ΜΕΣΕΣ ΤΙΜΕΣ

        // ΠΑΙΡΝΟΥΜΕ ΤΗ ΜΕΣΗ ΤΙΜΗ ΤΗΣ ΠΡΟΗΓΟΥΜΕΝΗΣ ΜΕΡΑΣ ΤΟΥ ΠΡΟΙΟΝΤΟΣ ΠΟΥ ΥΠΟΒΑΛΛΕΙ Ο ΧΡΗΣΤΗΣ ΓΙΑ ΝΑ ΤΗ ΣΥΓΚΡΙΝΟΥΜΕ ΜΕ ΤΗ ΝΕΑ
        $f=['name'=>$stored_product];
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
        $filter_prod=['name'=>$stored_product];
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

        // ΑΝ ΑΠΟΘΗΚΕΥΜΕΝΗ ΤΙΜΗ ΜΙΚΡΟΤΕΡΗ ΑΠΟ Α ΑΛΛΑ ΟΧΙ ΑΠΟ Β Η ΠΡΟΣΦΟΡΑ ΑΝΑΝΕΩΝΕΤΑΙ ΓΙΑ ΜΙΑ ΕΒΔΟΜΑΔΑ ΑΚΟΜΑ
        if($stored_offer_price < $pr_li && $stored_offer_price < $pr_lim){

            $client2 = new MongoDB\Client('mongodb://localhost:27017');
            $collection = $client2->$databaseName->$collectionName;
            $filter_for_offer = ['username' => $stored_username,'product_name' => $stored_product, 'shop' => $stored_shop];
            $update_offer = ['$set' => ['expiry_date' => date('d/m/Y', strtotime('+7 days'))]];
            $insert_offer = $collection->updateOne($filter_for_offer, $update_offer);

        }
        //ΑΝ ΑΠΟΘΗΚΕΥΜΕΝΗ ΤΙΜΗ ΜΙΚΡΟΤΕΡΗ ΑΠΟ Α ΑΛΛΑ ΟΧΙ ΑΠΟ Β Η ΠΡΟΣΦΟΡΑ ΑΝΑΝΕΩΝΕΤΑΙ ΓΙΑ ΜΙΑ ΕΒΔΟΜΑΔΑ ΑΚΟΜΑ
        elseif($stored_offer_price < $pr_li && $stored_offer_price > $pr_lim){

            $client2 = new MongoDB\Client('mongodb://localhost:27017');
            $collection = $client2->$databaseName->$collectionName;
            $filter_for_offer = ['username' => $stored_username,'product_name' => $stored_product, 'shop' => $stored_shop];
            $update_offer = ['$set' => ['expiry_date' => date('d/m/Y', strtotime('+7 days'))]];
            $insert_offer = $collection->updateOne($filter_for_offer, $update_offer);

        }
        //ΑΝ ΑΠΟΘΗΚΕΥΜΕΝΗ ΤΙΜΗ ΜΙΚΡΟΤΕΡΗ ΑΠΟ Β ΑΛΛΑ ΟΧΙ ΑΠΟ Α Η ΠΡΟΣΦΟΡΑ ΑΝΑΝΕΩΝΕΤΑΙ ΓΙΑ ΜΙΑ ΕΒΔΟΜΑΔΑ ΑΚΟΜΑ
        elseif($stored_offer_price > $pr_li && $stored_offer_price < $pr_lim){

            $client2 = new MongoDB\Client('mongodb://localhost:27017');
            $collection = $client2->$databaseName->$collectionName;
            $filter_for_offer = ['username' => $stored_username,'product_name' => $stored_product, 'shop' => $stored_shop];
            $update_offer = ['$set' => ['expiry_date' => date('d/m/Y', strtotime('+7 days'))]];
            $insert_offer = $collection->updateOne($filter_for_offer, $update_offer);

        }
        // ΣΒΗΝΕΤΑΙ Η ΠΡΟΣΦΟΡΑ ΑΝ ΔΕΝ ΙΣΧΥΕΙ ΤΙΠΟΤΑ ΑΠΟ ΤΑ ΠΑΡΑΠΑΝΩ
        else{

            $client2 = new MongoDB\Client("mongodb://localhost:27017");
            $collection = $client2->$databaseName->$collectionName;
            $delete_res = $collection->deleteOne([
                'expiry_date' => $stored_expiry_date,
                'product_name' => $stored_product,
                'shop' => $stored_shop,
                'username' => $stored_username,
            ]);

        }

    }
}
