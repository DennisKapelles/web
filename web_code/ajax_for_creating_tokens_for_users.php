<?php

include 'config.php';
require_once 'vendor/autoload.php';

session_start();

// ΠΑΙΡΝΕΙ ΤΟΥΣ ΧΡΗΣΤΕΣ ΠΟΥ ΕΙΝΑΙ ΗΔΗ ΜΕΣΑ ΣΤΟΝ ΠΙΝΑΚΑ tokens ΚΑΙ ΤΟΥ ΒΑΖΕΙ ΣΕ ΛΙΣΤΑ
$fil=[];
$opt=[];
$query = new MongoDB\Driver\Query($fil, $opt);
$result = $client->executeQuery('web_project_database.tokens', $query);
$users_tokens = array("!");

foreach($result as $res1){
    $stored_tok_username = $res1->username;
    $users_tokens[] = $stored_tok_username;
}

// ΠΑΙΡΝΕΙ ΟΛΟΥΣ ΤΟΥΣ ΧΡΗΣΤΕΣ ΠΟΥ ΕΙΝΑΙ ΕΓΓΕΓΡΑΜΕΝΟΙ ΜΕΣΑ ΣΤΟΝ ΠΙΝΑΚΑ users
// ΓΙΑ ΝΑ ΦΤΙΑΞΟΥΜΕ ΣΕ ΟΣΟΥΣ ΧΡΕΙΑΖΕΤΑΙ ΕΓΓΡΑΦΗ ΣΤΟΝ ΠΙΝΑΚΑ tokens
$filter=['type' => 'user'];
$options=[];
$query = new MongoDB\Driver\Query($filter, $options);
$resu = $client->executeQuery('web_project_database.users', $query);

foreach($resu as $res2){

    $stored_username = $res2->username;

    //ΓΙΑ ΚΑΘΕ ΧΡΗΣΤΗ ΠΟΥ ΥΠΑΡΧΕΙ ΣΤΟΝ ΠΙΝΑΚΑ users ΕΛΕΓΧΟΥΜΕ ΑΝ ΥΠΑΡΧΕΙ ΕΓΓΡΑΦΗ ΤΟΥ ΣΤΟΝ ΠΙΝΑΚΑ tokens
    // ΑΝ ΔΕΝ ΥΠΑΡΧΕΙ ΤΟΤΕ ΔΗΜΙΟΥΡΓΟΥΜΕ ΜΙΑ ΕΓΓΡΑΦΗ ΓΙΑ ΤΟΝ ΣΥΓΚΕΚΡΙΜΕΝΟ ΧΡΗΣΤΗ ΣΤΟΝ ΠΙΝΑΚΑ tokens
    if (!in_array($stored_username, $users_tokens)){

        $bulkWrite = new MongoDB\Driver\BulkWrite;
        $token = ["username" => $stored_username,"tokens_created_for_this_month" => 0, "tokens_distribution_for_this_month" => 0,"tokens_previous_month_user" => 0,"tokens_current_month_user" => 0,"total_tokens_user" => 0];
        $bulkWrite->insert($token);
        $client->executeBulkWrite('web_project_database.tokens', $bulkWrite);

    }
}
