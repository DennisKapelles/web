<?php 

include ('config.php');
error_reporting(0);
session_start();  


if (isset($_POST['submit'])) 
{

	$username = $_POST['username'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$conpassword = $_POST['conpassword'];

	// Validate password strength
	$uppercase = preg_match('@[A-Z]@', $password);
	$number    = preg_match('@[0-9]@', $password);
	$specialChars = preg_match('@[^\w]@', $password);

	// Check if password has at least one number, one special character, one upper letter and his lenght is bigger than 8
	// Message if password does not meet the requirements
	if(!$uppercase || !$number || !$specialChars || strlen($password) < 8)
	{
        $error[] = "Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.";
	}
	else if ($password == $conpassword) 
	{
		// Registration of user has been completed
		$filter=["email"=> $email];
		$options=[];
		$query = new MongoDB\Driver\Query($filter, $options);
		$result = $client->executeQuery('web_project_database.users', $query);

		foreach($result as $res) 
		{
			$storedemail = $res;
		}

		if (!$storedemail) 
		{
			$bulkWrite = new MongoDB\Driver\BulkWrite;
			$doc = ['username' => $username, 'email' => $email, 'password' => $password, 'type'=> 'user', 'register_date' => date("d/m/Y")];
			$bulkWrite->insert($doc);
			$client->executeBulkWrite('web_project_database.users', $bulkWrite);

			if ($client) 
			{
                $error[] = "User Registration Completed.";
				$username = "";
				$email = "";
				$_POST['password'] = "";
				$_POST['conpassword'] = "";
			} 
		}
		// Message if email already exists 
		else 
		{
            $error[] = "User Already Exists";
		}	
	}
	// Message if password not matched(Password and Confirm Password are different) 
	else 
	{
        $error[] = "Password Not Matched.";
	}
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Form</title>

    <link rel="stylesheet" href="style.css">

</head>
<body>

    <div class="form-container">
        <form action="" method="post">
            <h3>Register Form</h3>
            <?php 

            if(isset($error)){
                foreach($error as $error){
                    echo '<span class="error-msg">'.$error.'</span>';
                }
            };
            
            ?>
            <input type="text" name="username" required placeholder="Enter your username">
            <input type="email" name="email" required placeholder="Enter your email">
            <input type="password" name="password" required placeholder="Enter your password">
            <input type="password" name="conpassword" required placeholder="Confirm your password">
            <input type="submit" name="submit" value="Create Account" class="form-btn">
            <p>Already have an account? <a href="login_form.php">Login Here</a></p>
        </form>
    </div>

</body>
</html>