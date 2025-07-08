<?php

include 'config.php';
require_once 'vendor/autoload.php';

session_start();

// ΠΑΙΡΝΕΙ ΤΟΥΣ ΧΡΗΣΤΕΣ ΠΟΥ ΕΙΝΑΙ ΗΔΗ ΜΕΣΑ ΣΤΟΝ ΠΙΝΑΚΑ Score ΚΑΙ ΤΟΥ ΒΑΖΕΙ ΣΕ ΛΙΣΤΑ
$fil=[];
$opt=[];
$query = new MongoDB\Driver\Query($fil, $opt);
$result = $client->executeQuery('web_project_database.Score', $query);
$users_score = array("!");

foreach($result as $res1){
    $stored_sc_username = $res1->username;
    $users_score[] = $stored_sc_username;
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
    if (!in_array($stored_username, $users_score)){

        $bulkWrite = new MongoDB\Driver\BulkWrite;
        $score = ["username" => $stored_username,"total_score" => 0, "score_previous_month" => 0,"current_score" => 0];
        $bulkWrite->insert($score);
        $client->executeBulkWrite('web_project_database.Score', $bulkWrite);

    }
}
