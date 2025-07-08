<?php

include 'config.php';

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login_form.php");
}
//echo $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="make_an_offer_page.css">
<!--    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <title>Make an Offer Page</title>

</head>
<body>

<!--    <div>-->
<!--        <select>-->
<!--            <option value="">Βρεφικά Είδη</option>-->
<!--            <option value="">Καθαριότητα</option>-->
<!--            <option value="">Ποτά - Αναψυκτικά</option>-->
<!--            <option value="">Προσωπική φροντίδα</option>-->
<!--            <option value="">Τρόφιμα</option>-->
<!--            <option value="">Αντισηπτικά</option>-->
<!--            <option value="">Προστασία Υγείας</option>-->
<!--            <option value="">Για κατοικίδια</option>-->
<!--        </select>-->
<!--    </div>-->
<!--    <div class="right">-->
<!--        <h2>Products</h2>-->
<!--        <div class="product-wrapper">-->
<!---->
<!--        </div>-->
<!--    </div>-->


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
    <!-- HTML Markup -->
    <!--<label for="searchBox">SEARCH BOX</label>-->
    <input type="text" id="searchBox" placeholder="Search a product">
    <div id="suggestionsContainer" style="display: none;">
        <ul id="suggestionsList"></ul>
    </div>

    <form>
        <label for="product">Product:</label><br>
        <input type="text" id="product" name="product" class="form-field"><br>
        <br>
        <label for="price">Price:</label><br>
        <input type="number" id="price" name="price"  min="0.1" class="form-field"><br>
        <br>
        <input type="submit" value="Post" class="form-submit">
    </form>
</div>


<script>

    $(".form-submit").click(function(event) {
        event.preventDefault(); // Prevent the form from being submitted

        var product = $("#product").val();
        var price = $("#price").val();
        var shop_name = sessionStorage.getItem('shop');

        $.ajax({
            type: "POST",
            url: "ajax_insert_an_offer.php",
            data: { product: product, price: price, shop_name: shop_name},
            success: function(response) {
                console.log("Data written to database");
                alert(response);
            }
        });
    });

    function search_product(){
        $(function (){
            $.ajax({
                type: 'GET',
                url: 'get_products.php',
                success: function (data){
                    let products = JSON.parse(data);
                    //console.log(products);

                    // =========Define an array of product names
                    // ==========const products = ['tomato', 'tomatopolto', 'rigani', 'alati', 'alataki'];

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
                                        //searchBox.value = product;
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

    search_product();
    //var shop_name = sessionStorage.getItem('shop');
    //console.log(shop_name);


</script>



</body>
</html>