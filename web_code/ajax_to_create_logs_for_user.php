<?php

include 'config.php';
require_once 'vendor/autoload.php';

session_start();

// ΠΑΙΡΝΕΙ ΤΟΥΣ ΧΡΗΣΤΕΣ ΠΟΥ ΕΙΝΑΙ ΗΔΗ ΜΕΣΑ ΣΤΟΝ ΠΙΝΑΚΑ logs ΚΑΙ ΤΟΥ ΒΑΖΕΙ ΣΕ ΛΙΣΤΑ
$fil=[];
$opt=[];
$query = new MongoDB\Driver\Query($fil, $opt);
$result = $client->executeQuery('web_project_database.logs', $query);
$users_logs = array("!");

foreach($result as $res1){
    $stored_log_username = $res1->username;
    $users_logs[] = $stored_log_username;
}

// ΠΑΙΡΝΕΙ ΟΛΟΥΣ ΤΟΥΣ ΧΡΗΣΤΕΣ ΠΟΥ ΕΙΝΑΙ ΕΓΓΕΓΡΑΜΕΝΟΙ ΜΕΣΑ ΣΤΟΝ ΠΙΝΑΚΑ users
// ΓΙΑ ΝΑ ΦΤΙΑΞΟΥΜΕ ΣΕ ΟΣΟΥΣ ΧΡΕΙΑΖΕΤΑΙ ΕΓΓΡΑΦΗ ΣΤΟΝ ΠΙΝΑΚΑ Score
$filter=['type' => 'user'];
$options=[];
$query = new MongoDB\Driver\Query($filter, $options);
$resu = $client->executeQuery('web_project_database.users', $query);

foreach($resu as $res2){

    $stored_username = $res2->username;

    //ΓΙΑ ΚΑΘΕ ΧΡΗΣΤΗ ΠΟΥ ΥΠΑΡΧΕΙ ΣΤΟΝ ΠΙΝΑΚΑ users ΕΛΕΓΧΟΥΜΕ ΑΝ ΥΠΑΡΧΕΙ ΕΓΓΡΑΦΗ ΤΟΥ ΣΤΟΝ ΠΙΝΑΚΑ Score
    // ΑΝ ΔΕΝ ΥΠΑΡΧΕΙ ΤΟΤΕ ΔΗΜΙΟΥΡΓΟΥΜΕ ΜΙΑ ΕΓΓΡΑΦΗ ΓΙΑ ΤΟΝ ΣΥΓΚΕΚΡΙΜΕΝΟ ΧΡΗΣΤΗ ΣΤΟΝ ΠΙΝΑΚΑ Score
    if (!in_array($stored_username, $users_logs)){

        $bulkWrite = new MongoDB\Driver\BulkWrite;
        $log = ["username" => $stored_username,"user_history" => []];
        $bulkWrite->insert($log);
        $client->executeBulkWrite('web_project_database.logs', $bulkWrite);

    }
}
