<?php 

include 'config.php';
session_start();
error_reporting(0);


if (isset($_POST['submit'])) {
	$username = $_POST['username'];
	$password = $_POST['password'];

	$filter=["username"=> $username, "password"=> $password];
	$options=[];
	$query = new MongoDB\Driver\Query($filter, $options);
    $result = $client->executeQuery('web_project_database.users', $query);

	foreach($result as $res) 
	{
		$storedusername = $res->username;
		$storedpass = $res->password;
        $storedtype = $res->type;
	}
	// Check if user's email and password are right!
	if ($storedusername && $storedpass && $storedtype == "user")
	{
		$_SESSION['username'] = $res->username;
		header("Location: user_page.php");
	}
    elseif($storedusername && $storedpass && $storedtype == "admin"){
        $_SESSION['username'] = $res->username;
		header("Location: admin_page.php");
    }
	else 
	{
        $error[] = "Username or Password is Wrong.";
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>

    <link rel="stylesheet" href="style.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>

        $(document).ready(function() {
            $.ajax({
                type: 'POST',
                url: 'ajax_to_create_logs_for_user.php',
                success: function(response) {
                    console.log('Logs ajax call: ' + response);
                    $.ajax({
                        type: 'POST',
                        url: 'ajax_for_creating_score_for_users.php',
                        success: function(response) {
                            console.log('Score ajax call: ' + response);
                            // code to execute after the first ajax call is successful
                            $.ajax({
                                type: 'POST',
                                url: 'ajax_for_creating_tokens_for_users.php',
                                success: function(response) {
                                    console.log('Tokens ajax call: ' + response);
                                    // code to execute after the second ajax call is successful
                                    $.ajax({
                                        type: 'POST',
                                        url: 'ajax_for_updating_the_dates_of_avg_prices.php',
                                        success: function(response) {
                                            console.log('Update dates of Avg_prices ajax call: ' + response);
                                            // code to execute after the third ajax call is successful
                                            $.ajax({
                                                type: 'POST',
                                                url: 'ajax_for_deleting_the_expired_offers.php',
                                                success: function(response) {
                                                    console.log('Delete expiry dates ajax call: ' + response);
                                                    // code to execute after the fourth ajax call is successful
                                                    $.ajax({
                                                        type: 'POST',
                                                        url: 'ajax_for_updating_tokens_on_first_and_last_day_of_month.php',
                                                        success: function(response) {
                                                            console.log('Update Tokens in the first and last day of the month ajax call: ' + response);
                                                            // code to execute after the fifth ajax call is successful
                                                            $.ajax({
                                                                type: 'POST',
                                                                url: 'ajax_for_updating_the_score_in_the_end_of_month.php',
                                                                success: function(response) {
                                                                    console.log('Update Score in the last day of the month ajax call: ' + response);
                                                                }
                                                            });
                                                        }
                                                    });
                                                }
                                            });
                                        }
                                    });
                                }
                            });
                        }
                    });
                }
            });
        });
    </script>

</head>
<body>

    <div class="form-container">
        <form action="" method="post">
            <h3>Login Form</h3>
            <?php 
            if(isset($error)){
                foreach($error as $error){
                    echo '<span class="error-msg">'.$error.'</span>';
                }
            };
            ?>
            <input type="username" name="username" required placeholder="Enter your username">
            <input type="password" name="password" required placeholder="Enter your password">
            <input type="submit" name="submit" value="Log in" class="form-btn">
            <p>Don't have an account? <a href="register_form.php">Register Here</a></p>
        </form>
    </div>

</body>
</html>