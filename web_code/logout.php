<?php 

include 'config.php';

session_start();

session_destroy();

header("Location: login_form.php");

?>