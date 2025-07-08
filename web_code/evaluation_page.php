<?php

include 'config.php';

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login_form.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="make_evaluation_page.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

        <!--    Script for like/dislike buttons-->
    <script src="https://use.fontawesome.com/fe459689b4.js"></script>
    <title>Evaluation Page</title>
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



<div class="container">
    <input type="text" id="searchBox" placeholder="Search a product">
    <div id="suggestionsContainer" style="display: none;">
        <ul id="suggestionsList"></ul>
    </div>

    <form id="my_form">
        <label for="product">Product for Evaluation:</label><br>
        <input type="text" id="product" name="product" class="form-field">
        <button class="btn" id="green"><i class="fa fa-thumbs-up fa-lg" aria-hidden="true"></i></button>
        <button class="btn" id="red"><i class="fa fa-thumbs-down fa-lg" aria-hidden="true"></i></button><br>
        <br>
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" class="form-field">
        <br>
        <br>
        <label for="stock">Stock:</label><br>
        <select name="stock" id="stock">
            <option value="yes">Yes</option>
            <option value="no">No</option>
        </select>
        <br>
        <br>
        <br>
        <input type="submit" value="Update Stock" class="form-submit">
    </form>

    <!--For display of offers table-->
    <div id="forTable"></div>
</div>

<script>

    // Code to update stock of products
    $(".form-submit").click(function(event) {
        event.preventDefault(); // Prevent the form from being submitted

        var product = $("#product").val();
        var stock = document.getElementById("stock").value;
        var shop_name = sessionStorage.getItem('shop');
        var username = $("#username").val();

        $.ajax({
            type: "POST",
            url: "update_stock.php",
            data: { product: product, stock: stock, shop_name: shop_name, username: username},
            success: function(response) {
                console.log("Data written to database");
                alert(response);
                eval_markers();
                // Clear the product field
                $('#my_form').children("#product").val('');
                $('#my_form').children("#username").val('');
            }
        });
    });

    // Code for like and dislike buttons
    var btn1 = document.querySelector('#green');
    var btn2 = document.querySelector('#red');


    btn1.addEventListener('click', function() {

        var product = $("#product").val();
        var shop_name = sessionStorage.getItem('shop');
        var username = $("#username").val();

        $.ajax({
            type: "POST",
            url: "like_evaluation.php",
            data: { product: product, shop_name: shop_name, username: username},
            success: function(response) {
                console.log("Data written to database");
                alert(response);
            }
        });
        eval_markers();
        if (btn2.classList.contains('red')) {
            btn2.classList.remove('red');
        }
        this.classList.toggle('green');

    });

    btn2.addEventListener('click', function() {

        var product = $("#product").val();
        var shop_name = sessionStorage.getItem('shop');
        var username = $("#username").val();

        $.ajax({
            type: "POST",
            url: "dislike_evaluation.php",
            data: { product: product, shop_name: shop_name, username: username},
            success: function(response) {
                console.log("Data written to database");
                alert(response);
            }
        });
        eval_markers();
        if (btn1.classList.contains('green')) {
            btn1.classList.remove('green');
        }
        this.classList.toggle('red');
    });


    // Function to create the offers table
    function createTableByForLoop(data)
    {
        var eTable="<center><table class='leaderboard-results'><thead><tr><th colspan='11'>List of shop offers</th></tr><tr><th>Product Name</th><th>Shop Name</th><th>Username</th><th>Price</th><th>Likes</th><th>Dislikes</th><th>Stock</th><th>Offer Date</th><th>Expiry Date</th><th>Criteria</th><th>User Score</th></tr></thead><tbody></center>"
        for(var i=0; i<data.length;i++)
        {
            eTable += "<tr>";
            eTable += "<td>"+data[i]['product_name']+"</td>";
            eTable += "<td>"+data[i]['shop_name']+"</td>";
            eTable += "<td>"+data[i]['user_name']+"</td>";
            eTable += "<td>"+data[i]['price']+"</td>";
            eTable += "<td>"+data[i]['likes']+"</td>";
            eTable += "<td>"+data[i]['dislikes']+"</td>";
            eTable += "<td>"+data[i]['stock']+"</td>";
            eTable += "<td>"+data[i]['offerdate']+"</td>";
            eTable += "<td>"+data[i]['expirydate']+"</td>";
            if (data[i]['criteria'] === 'yes'){
                eTable += "<td>" + "\u2713" + "</td>";
            }
            else if (data[i]['criteria'] === 'no'){
                eTable += "<td>"+ "x" + "</td>";
            }
            eTable += "<td>"+data[i]['score']+"</td>";
            eTable += "</tr>";
        }
        eTable +="</tbody></table>";
        $('#forTable').html(eTable);
    }

    // Fuction to display offers info
    function eval_markers(){
        var shop_name = sessionStorage.getItem('shop');
        $(function (){
            $.ajax({
                type: 'POST',
                url: 'ajax_eval_offers.php',
                data: {shop_name: shop_name},
                success: function (data) {
                    let res = JSON.parse(data);
                    var data_eval = [];
                    for (i in res) {

                        let poi = {
                            product_name: res[i][0],
                            shop_name: res[i][1],
                            user_name: res[i][2],
                            price: res[i][3],
                            likes: res[i][4],
                            dislikes: res[i][5],
                            stock: res[i][6],
                            offerdate: res[i][7],
                            expirydate: res[i][8],
                            criteria:res[i][9],
                            score: res[i][10],
                        };
                        data_eval.push(poi)
                    }
                    createTableByForLoop(data_eval);
                    console.log(data_eval);
                }
            });
        });
    }

    function search_product(){
        $(function (){
            var shop_name = sessionStorage.getItem('shop');
            $.ajax({
                type: 'POST',
                url: 'products_evaluation.php',
                data: {shop_name: shop_name},
                success: function (data){
                    let products = JSON.parse(data);
                    //console.log(products);

                    //Get the search box and suggestions container elements
                    const searchBox = document.getElementById('searchBox');
                    const suggestionsContainer = document.getElementById('suggestionsContainer');
                    const suggestionsList = document.getElementById('suggestionsList');
                    const productBox = document.getElementById('product');

                    // Add an event listener to the search box to detect when the user enters a search term
                    searchBox.addEventListener('input', function() {
                        // Get the search term entered by the user
                        const searchTerm = this.value.toLowerCase();

                        // Clear the suggestions list
                        suggestionsList.innerHTML = '';

                        // If the search term is not empty, show the suggestions container and loop through the array of products
                        if (searchTerm) {
                            suggestionsContainer.style.display = 'block';
                            for (const product of products) {
                                // If the product name starts with the search term, add it to the suggestions list
                                if (product.toLowerCase().startsWith(searchTerm)) {
                                    const suggestion = document.createElement('li');
                                    suggestion.textContent = product;
                                    suggestionsList.appendChild(suggestion);

                                    // Add an event listener to the list item to update the search box with the product name when clicked
                                    suggestion.addEventListener('click', function() {
                                        productBox.value = product;
                                        $(function (){
                                            var product = $("#product").val();
                                            var shop = sessionStorage.getItem('shop');
                                            // var product_name = product;
                                            console.log(product);
                                            console.log(shop);
                                            $.ajax({
                                                type: 'POST',
                                                url:  'grayed_out.php',
                                                data: {shop: shop, product: product},
                                                success: function (data) {
                                                    let products = JSON.parse(data);
                                                    var data_gray = [];
                                                    for (i in products) {

                                                        let poi = {
                                                            stock: products[i],
                                                        };
                                                        data_gray.push(poi)
                                                    }
                                                    // Make buttons grayed out if stock is no
                                                    console.log(data_gray[0].stock);
                                                    if (data_gray[0].stock === "no") {
                                                        btn1.disabled = true
                                                        btn2.disabled = true
                                                    } else{
                                                        btn1.disabled = false
                                                        btn2.disabled = false
                                                    }
                                                }
                                            });
                                        });
                                        searchBox.value = '';
                                        suggestionsContainer.style.display = 'none';
                                    });
                                }
                            }
                        } else {
                            // If the search term is empty, hide the suggestions container
                            suggestionsContainer.style.display = 'none';
                        }
                    });
                }
            });
        });

    }
    eval_markers();
    search_product();
</script>
</body>
</html>