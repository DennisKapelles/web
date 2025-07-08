<?php

session_start();

// Update the session variable
$_SESSION['username'] = $_POST['new_session_name'];
