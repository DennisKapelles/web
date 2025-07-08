<?php
// Require for MongoDB00
require 'C:/xampp/phpMyAdmin/vendor/autoload.php';

//require 'C:\Users\Bill\vendor/autoload.php';

try {
    //phpinfo();
    //composer require mongodb/mongodbphpinfo();
    // connect to OVHcloud Public Cloud Databases for MongoDB (cluster in version 4.4, MongoDB PHP Extension in 1.8.1)
    $client = new MongoDB\Driver\Manager('mongodb://localhost:27017');
    //echo "Connection to database successful!";
}
catch (Throwable $e) {
    // catch throwables when the connection is not a success
    echo "Captured Throwable for connection : " . $e->getMessage() . PHP_EOL;
}

