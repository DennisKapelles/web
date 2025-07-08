<?php

include 'config.php';

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login_form.php");
}
//echo $_SESSION['username'];

echo '<input type="hidden" id="session-username" value="' . $_SESSION['username'] . '">';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="user_profile.css">
    <!--    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <title>User Profile</title>

</head>
<body>

<nav class="navbar">

    <!-- Welcome -->
    <h2>Welcome User <span><?php echo $_SESSION['username']?></span></h2>

    <!-- NAVIGATION MENU -->
    <ul class="nav-links">
        <!-- NAVIGATION MENUS -->
        <div class="menu">
            <li><a href="user_page.php">User Page</a></li>
            <li><a href="user_profile.php">Profile</a></li>
            <li><a href="login_form.php">Log out</a></li>
        </div>
    </ul>
</nav>

<br>

<div class="container">
    <div>
        <label for="change-username-form">Change Username and Password</label>
        <form id="change-username-form">
            <label for="new_username">New Username:</label><br>
            <input type="text" id="new_username" name="new_username"><br>
            <input type="submit" value="Change username" class="form-submit">
        </form>
        <br>
        <form id="change-password-form">
            <label for="new_password">New Password:</label><br>
            <input type="password" id="new_password" name="new_password"><br>
            <input type="submit" value="Change password" class="form-submit">
        </form>
    </div>
    <br>
    <label for="tokens">User Tokens And Scores</label>
    <div id="tokens">
        <div id="tokens_list"></div>
    </div>
    <div id="score">
        <div id="score_list"></div>
    </div>
    <br>
    <label for="user_history">User History</label>
    <div id="user_history"></div>
</div>


<script>

    // Add a submit event listener to the form
    $('#change-username-form').submit(function(event) {
        // Prevent the default form submission behavior
        event.preventDefault();

        // Get the old username and new username from the form
        let sessionUsername = document.getElementById('session-username').value;
        console.log(sessionUsername);
        let newUsername = $('#new_username').val();
        console.log(newUsername);

        // Use jQuery's $.ajax method to send a POST request to the server
        $.ajax({
            type: 'POST',
            url: 'ajax_for_change_the_username.php',
            data: {session_Username: sessionUsername, new_username: newUsername},
            success: function(message) {
                // Display the message to the user
               alert(message);
                // Send an AJAX request to update the session variable
                $.ajax({
                    type: 'POST',
                    url: 'ajax_to_update_session_variable.php',
                    data: {new_session_name: newUsername},
                    success: function(data) {
                        location.reload();
                    }
                });
            }
        });
    });


    $('#change-password-form').submit(function(event) {
        // Prevent the default form submission behavior
        event.preventDefault();

        // Get the old password and new password from the form
        let sessionUsername = document.getElementById('session-username').value;
        console.log(sessionUsername);
        let new_password = $('#new_password').val();
        console.log(new_password);

        if (new_password.length < 8) {
            alert("The new password must be at least 8 characters long");
            return;
        }
        if (!/[A-Z]/.test(new_password)) {
            alert("The new password must contain at least one upper case letter");
            return;
        }
        if (!/[0-9]/.test(new_password)) {
            alert("The new password must contain at least one number");
            return;
        }
        if (!/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(new_password)) {
            alert("The new password must contain at least one symbol");
            return;
        }

        // If the validation checks pass, use jQuery's $.ajax method to send a POST request to the server
        $.ajax({
            type: 'POST',
            url: 'ajax_for_change_the_password.php',
            data: {session_Username: sessionUsername, new_password: new_password
            },
            success: function(message) {
                // Display the message to the user
                alert(message);
            }
        });
    });

    let sessionUsername = document.getElementById('session-username').value;
    $.ajax({
        type: 'POST',
        url: 'ajax_to_take_tokens_previous_month_and_total_tokens.php',
        data: {session_Username: sessionUsername},
        success: function(tokens) {
            let res = JSON.parse(tokens);
            //console.log(res);
            let data_tokens = [];
            for (i in res){

                let tokens = {
                    tokens_previous_month: res[i][0],
                    total_tokens:res[i][1]
                };
                data_tokens.push(tokens);
            }
            //console.log(data_tokens);
            //console.log(data_tokens[0].tokens_previous_month);
            //console.log(data_tokens[0].total_tokens);
            $('#tokens_list').append(`<li>Tokens Previous Month: <span>${data_tokens[0].tokens_previous_month}</span></li><li>Total Tokens: <span>${data_tokens[0].total_tokens}</span></li>`);

            $.ajax({
                type: 'POST',
                url: 'ajax_to_take_total_score_and_current_score.php',
                data: {session_Username: sessionUsername},
                success: function(tokens) {
                    let res = JSON.parse(tokens);
                    //console.log(res);
                    let data_score = [];
                    for (i in res){

                        let score = {
                            current_score: res[i][0],
                            total_score:res[i][1]
                        };
                        data_score.push(score);
                    }
                    //console.log(data_score);
                    //console.log(data_score[0].current_score);
                    //console.log(data_score[0].total_score);
                    $('#tokens_list').append(`<li>Current Score: <span>${data_score[0].current_score}</span></li><li>Total Score: <span>${data_score[0].total_score}</span></li>`);
                }
            });
        }
    });


    $.ajax({
        type: 'POST',
        url: 'ajax_to_get_the_logs_of_a_user.php',
        data: {session_Username: sessionUsername},
        dataType: 'json',
        success: function(data) {
            console.log(data);
            // Loop through the "user_history" array and append each element as a list item to the "history" div
            $.each(data.user_history, function(index, value) {
                $('#user_history').append('<li>' + value + '</li>');
            });
        }
    });

</script>



</body>
</html>