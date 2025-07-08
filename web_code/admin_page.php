<?php 

include 'config.php';
//include 'user_page.php';
//
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login_form.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!--for charts-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>

    <!--                    ==========Map==========                   -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
          integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
          crossorigin=""/>

    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
            integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM="
            crossorigin=""></script>


    <!--                    ==========Control Search==========                   -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-search/3.0.2/leaflet-search.min.js"></script>
    <!--<script src="https://cdn.jsdelivr.net/npm/leaflet-search@3.0.2/dist/leaflet-search.min.css"></script>-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet-search@3.0.2/dist/leaflet-search.min.css"/>
    <!--(error) <script src="https://cdn.jsdelivr.net/npm/leaflet-search@3.0.2/dist/leaflet-search.min.css"></script>-->

    <title>Admin Page</title>

    <link rel="stylesheet" type="text/css" href="admin_page.css">

</head>
<body>

<nav class="navbar">

    <!-- Welcome -->
    <h2>Welcome User <span><?php echo $_SESSION['username']?></span></h2>

    <!-- NAVIGATION MENU -->
    <ul class="nav-links">
        <!-- NAVIGATION MENUS -->
        <div class="menu">
            <li><a href="login_form.php">Log out</a></li>
        </div>
    </ul>
</nav>

<div class="containers">
    <div>
        <form id="shop_filter_form">
            <label for="shop_filter">Filter by shop name:</label><br>
            <select id="shop_filter">
                <option value="-">--------</option>
                <option value="3Α Αράπης">3Α Αράπης</option>
                <option value="ΑΒ Βασιλόπουλος">ΑΒ Βασιλόπουλος</option>
                <option value="ΑΒ Shop & Go">ΑΒ Shop & Go</option>
                <option value="Ανδρικόπουλος">Ανδρικόπουλος</option>
                <option value="Γαλαξίας">Γαλαξίας</option>
                <option value="Carna">Carna</option>
                <option value="Ena Cash And Carry">Ena Cash And Carry</option>
                <option value="Η Γωνιά">Η Γωνιά</option>
                <option value="Kaponis">Kaponis</option>
                <option value="Kiosk">Kiosk</option>
                <option value="Κρόνος">Κρόνος</option>
                <option value="Lidl">Lidl</option>
                <option value="Markoulas">Markoulas</option>
                <option value="Μασούτης">Μασούτης</option>
                <option value="Mini Market Παπανικολάου">Mini Market Παπανικολάου</option>
                <option value="Mini Market Σταθόπουλος">Mini Market Σταθόπουλος</option>
                <option value="Mini Market Τσαρούχας">Mini Market Τσαρούχας</option>
                <option value="My market">My market</option>
                <option value="Ορφανός Market">Ορφανός Market</option>
                <option value="Papakos">Papakos</option>
                <option value="Περίπτερο">Περίπτερο</option>
                <option value="Πόντος">Πόντος</option>
                <option value="Ρουμελιώτης">Ρουμελιώτης</option>
                <option value="Σκλαβενίτης">Σκλαβενίτης</option>
                <option value="Spar">Spar</option>
                <option value="Super Market Θεοδωρόπουλος">Super Market Θεοδωρόπουλος</option>
                <option value="The Mart">The Mart</option>
                <option value="Φίλιππας">Φίλιππας</option>
            </select>
            <button id="button_id">Filter</button>
            <!--                <input type="submit" value="Filter">-->
        </form>
    </div>
</div>

<div id="mapid" style="width: 1000px; height: 700px;"></div>

<br>
<br>

<center>
    <div class="buttons">

        <div class="action_btn">
            <form class="form" action="delete_products.php" method="post" enctype="multipart/form-data">
                <button onclick="alert('Τα δεδομένα διαγράφηκαν')">
                    Delete Product Data
                </button>
            </form>

            <form class="form" action="upload_products.php" method="post" enctype="multipart/form-data">
                <center><span class="drop-title">Update Products Data:</span></center>
                <br>
                <input type="file" name="products" id="fileToUpload" accept="application/json">
                <button onclick="alert('Το ανέβασμα του αρχείου ήταν επιτυχές')"> Upload Products Data</button>
            </form>

            <form class="form" action="upload_prices.php" method="post" enctype="multipart/form-data">
                <center><span class="drop-title">Update Prices Data:</span></center>
                <br>
                <input type="file" name="prices" id="fileToUpload" accept="application/json">
                <button onclick="alert('Το ανέβασμα του αρχείου ήταν επιτυχές')"> Upload Prices Data</button>
            </form>

            <form class="form" action="delete_pois.php" method="post" enctype="multipart/form-data">
                <button onclick="alert('Τα pois διαγράφηκαν')">
                    Delete Shops
                </button>
            </form>

            <br>
            <br>

            <button class="form" id="myBtn" type="submit" value='submit'>Show Leaderboard</button>

            <form class="form" action="upload_pois.php" method="post" enctype="multipart/form-data">
                <center><span class="drop-title">Update Pois Data:</span></center>
                <br>
                <input type="file" name="fileToUpload" id="fileToUpload" accept="application/json">
                <button onclick="alert('Το ανέβασμα του αρχείου ήταν επιτυχές')"> Upload Pois Data</button>
            </form>

            <button class="form" id="mybutton" type="submit" value='submit'>Delete Offer</button>

            <button class="form" id="button" type="submit" value='submit'>Charts</button>
            <br>
            <br>
            <br>
        </div>
    </div>
</center>

<br>

<div class="container-wrap" id="title">
    <section id="leaderboard">
        <nav class="ladder-nav">
            <div class="ladder-title" align="center">
                    <h1>Standings</h1>
            </div>
        </nav>
        <table id="rankings" class="leaderboard-results" width="100%">
            <thead>
            <tr>
                <th>Rank</th>
                <th>Username</th>
                <th>Total Score</th>
                <th>Previous Month Tokens</th>
                <th>Total Tokens</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </section>
    <div class="pagination">
        <ol id="numbers"></ol>
    </div>
</div>

<div id="charts">

    <form id="form">
        <label for="year">Year:</label>
        <select id="year">
            <?php for ($i = 2023; $i >= 2000; $i--) { ?>
                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
            <?php } ?>
        </select>
        <br>
        <label for="month">Month:</label>
        <select name="month" id="month">
            <option value="01">January</option>
            <option value="02">February</option>
            <option value="03">March</option>
            <option value="04">April</option>
            <option value="05">May</option>
            <option value="06">June</option>
            <option value="07">July</option>
            <option value="08">August</option>
            <option value="09">September</option>
            <option value="10">October</option>
            <option value="11">November</option>
            <option value="12">December</option>
        </select>
        <br>
        <button type="submit" width = "100%">Search Offers</button>
    </form>
    <canvas id="myChart"></canvas>
</div>

<div class="container">
    <div id="delete_offer">

        <input type="text" id="searchBox" placeholder="Search a product">
        <div id="suggestionsContainer" style="display: none;">
            <ul id="suggestionsList"></ul>
        </div>

        <form id="my_form">
            <label for="product">Product to Delete:</label><br>
            <input type="text" id="product" name="product" class="form-field">
            <br>
            <br>
            <label for="shop">Shop:</label><br>
            <input type="text" id="shop" name="shop" class="form-field">
            <br>
            <br>
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" class="form-field">
            <br>
            <br>
            <br>
            <input type="submit" value="Delete Offer" class="form-submit">
        </form>
        <!--For display of offers table-->
        <div id="forTable" width = "100%"></div>
    </div>
</div>

<script>

    // function getLocation() {
    //     if (navigator.geolocation)
    //     {
    //         navigator.geolocation.getCurrentPosition(showPosition);
    //     }
    //     else
    //     {
    //         x.innerHTML = "Geolocation is not supported by this browser.";
    //     }
    // }
    //
    // function showPosition(pos) {
    //     const crd = pos.coords;
    //
    //     console.log('Your current position is:');
    //     console.log(`Latitude : ${crd.latitude}`);
    //     console.log(`Longitude: ${crd.longitude}`);
    //     console.log(`More or less ${crd.accuracy} meters.`);
    //     let marker = L.marker ([pos.coords.latitude,pos.coords.longitude],{draggable: "true" });
    //     marker.addTo(map);
    //     marker.bindPopup("<b>Current location!</b>").openPopup();
    //     // L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    //     //     attribution:
    //     //         '&copy; <a href="http://openstreetmap.org"/OpenStreetMap</a> contributors' +
    //     //         ', Tiles courtesy of <a href="https://geo6.be/">GEO-6</a>',
    //     //     maxZoom: 18
    //     // }).addTo(map);
    // }
    // getLocation();

    let greyIcon = L.icon({
        iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-grey.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [0, 0]
    });

    let greenIcon = L.icon({
        iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [0, 0]
    });

    let redMarker = L.icon({
        iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [0, 0]
    });

    let yellowIcon = new L.Icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-yellow.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        //shadowSize: [41, 41]
        shadowSize: [0, 0]
    });


    let map = L.map('mapid').setView([38.2462420, 21.7350847], 20);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution:'&copy; <a href="http://openstreetmap.org"/OpenStreetMap</a> contributors' +', Tiles courtesy of <a href="https://geo6.be/">GEO-6</a>',
        maxZoom: 18
    }).addTo(map);

    let markersLayer = new L.LayerGroup();
    map.addLayer(markersLayer);

    //-----marker.on("click", markerClick);
    // Add an event listener to the marker that is triggered when the user drags and releases it

    offers_markers();

    var selected_shop = $("#shop_filter").val();
    console.log(selected_shop);
    $.ajax({
        type: "POST",
        url: "ajax_to_get_filter_shops.php",
        data: { shop_name: selected_shop },
        success: function(response) {
            // Parse the JSON response from the server
            let locations = JSON.parse(response);
            console.log("FILTER AJAX");
            console.log(locations);

            markersLayer.clearLayers();

            $.ajax({
                type: 'GET',
                url: 'ajax_to_check_filter_markers.php',
                success: function (data2){
                    let offers_markers_names = JSON.parse(data2);
                    //console.log(offers_markers_names);

                    // let filter_marker_layer = L.layerGroup();
                    // filter_marker_layer.addTo(map);

                    // Iterate through the locations and add markers to the map
                    for (let i = 0; i < locations.length; i++) {
                        let name = locations[i].name;
                        let coordinates = locations[i].coordinates;
                        let lon = coordinates[0];
                        let lat = coordinates[1];

                        let marker2 = L.marker([lat, lon],{title: name});

                        //  ΕΧΕΙ ΠΡΟΣΦΟΡΑ ΤΟ ΚΑΤΑΣΤΗΜΑ
                        if (offers_markers_names.includes(name)) {
                            marker2.setIcon(greenIcon);
                            //console.log("Distance:" + dist);
                            //console.log("\n");
                            // Send an AJAX request to the server using jQuery
                            $.ajax({
                                type: 'POST',
                                url: 'ajax_to_print_the_offers_for_markers.php',
                                data: {shop: name},
                                success: function(data) {
                                    // Do something with the data returned by the server, such as displaying it in an alert box
                                    console.log("DATA AJAX");
                                    let offers_marker = JSON.parse(data);
                                    console.log(offers_marker);

                                    // Create the content of the popup using the data
                                    var content = '<h1>' + name + '</h1>';
                                    content += '<ul>';
                                    for (var i = 0; i < offers_marker.length; i++) {
                                        content += '<h5>Product: ' + offers_marker[i]['product_name'] + '</h5>';
                                        content += '<li>Username: ' + offers_marker[i]['username'] + '</li>';
                                        content += '<li>Price: ' + offers_marker[i]['price'] + '</li>';
                                        content += '<li>Likes: ' + offers_marker[i]['likes'] + '</li>';
                                        content += '<li>Dislikes: ' + offers_marker[i]['dislikes'] + '</li>';
                                        content += '<li>Stock: ' + offers_marker[i]['stock'] + '</li>';
                                        content += '<li>Offer date: ' + offers_marker[i]['offer_date'] + '</li>';
                                        content += '<li>Expiry date: ' + offers_marker[i]['expiry_date'] + '</li>';
                                        if (offers_marker[i]['criteria'] === 'yes'){
                                            content += '<li>Criteria: '  + "\u2713" + '</li>';
                                        }
                                        else if (offers_marker[i]['criteria'] === 'no'){
                                            content += '<li>Criteria: '  + "x" + '</li>';
                                        }
                                        content += '<br>';
                                    }
                                    content += '</ul>';
                                    marker2.bindPopup(content);
                                    marker2.addTo(markersLayer);
                                }
                            });
                        }
                        // ΔΕΝ ΕΧΕΙ ΠΡΟΣΦΟΡΑ ΤΟ ΚΑΤΑΣΤΗΜΑ
                        else {
                            marker2.setIcon(greyIcon);
                            $.ajax({
                                type: 'POST',
                                url: 'ajax_to_print_the_offers_for_markers.php',
                                data: {shop: name},
                                success: function(data) {
                                    // Do something with the data returned by the server, such as displaying it in an alert box
                                    console.log("DATA AJAX");
                                    let offers_marker = JSON.parse(data);
                                    console.log(offers_marker);

                                    var content = '<h1>' + name + '</h1>';
                                    content += '<h3>' + 'ΔΕΝ ΥΠΑΡΧΟΥΝ ΠΡΟΣΦΟΡΕΣ' + '</h3>';
                                    marker2.bindPopup(content);
                                    marker2.addTo(markersLayer);
                                }
                            });
                        }
                    }
                }
            });
        }
    });


    function search_pop_up_simple(poi){
        return `<div>
                <h4>Όνομα καταστήματος:${poi}</h4>
                </div>`;
    }


    function offers_markers(){
        $(function (){
            $.ajax({
                type: 'GET',
                url: 'ajax_offers_marker.php',
                success: function (data){
                    let res = JSON.parse(data);
                    console.log(res);

                    markersLayer.clearLayers();

                    var data_loc = [];
                    for (i in res){

                        let poi = {
                            loc: [res[i][1], res[i][2]],
                            title: res[i][0],
                            product_name:res[i][3],
                            price:res[i][4],
                            likes:res[i][5],
                            dislikes:res[i][6],
                            stock:res[i][7],
                            offer_date:res[i][8],
                            criteria:res[i][9]
                        };
                        data_loc.push(poi)
                    }

                    for (i in data_loc)
                    {
                        let title = data_loc[i].title;

                        let loc = data_loc[i].loc;
                        // let marker = L.marker(L.latLng(loc),{title: title,icon: yellowIcon});
                        let marker1 = L.marker(L.latLng(loc),{title: title,shadowSize: [0, 0]});

                        // Send an AJAX request to the server using jQuery
                        $.ajax({
                            type: 'POST',
                            url: 'ajax_to_print_the_offers_for_markers.php',
                            data: {shop: title},
                            success: function(data) {
                                // Do something with the data returned by the server, such as displaying it in an alert box
                                console.log("DATA AJAX");
                                let offers_marker = JSON.parse(data);
                                console.log(offers_marker);

                                // Create the content of the popup using the data
                                var content = '<h1>' + title + '</h1>';
                                content += '<ul>';
                                for (var i = 0; i < offers_marker.length; i++) {
                                    content += '<h5>Product: ' + offers_marker[i]['product_name'] + '</h5>';
                                    content += '<li>Username: ' + offers_marker[i]['username'] + '</li>';
                                    content += '<li>Price: ' + offers_marker[i]['price'] + '</li>';
                                    content += '<li>Likes: ' + offers_marker[i]['likes'] + '</li>';
                                    content += '<li>Dislikes: ' + offers_marker[i]['dislikes'] + '</li>';
                                    content += '<li>Stock: ' + offers_marker[i]['stock'] + '</li>';
                                    content += '<li>Offer date: ' + offers_marker[i]['offer_date'] + '</li>';
                                    content += '<li>Expiry date: ' + offers_marker[i]['expiry_date'] + '</li>';
                                    if (offers_marker[i]['criteria'] === 'yes'){
                                        content += '<li>Criteria: '  + "\u2713" + '</li>';
                                    }
                                    else if (offers_marker[i]['criteria'] === 'no'){
                                        content += '<li>Criteria: '  + "x" + '</li>';
                                    }
                                    content += '<br>';
                                }
                                content += '</ul>';
                                marker1.bindPopup(content);
                                marker1.addTo(markersLayer);
                            }
                        });

                    }
                }
            });
        });
    }

    // ΤΗΝ ΘΕΛΟΥΜΕ (ΠΑΙΖΕΙ)
    function search_markers(){
        $(function (){
            $.ajax({
                type: 'GET',
                url: 'ajax_markers.php',
                success: function (data){
                    let res = JSON.parse(data);
                    var data_loc = [];
                    for (i in res){

                        let poi = {
                            loc: [res[i][1], res[i][2]],
                            title: res[i][0]
                        };
                        data_loc.push(poi)

                    }
                    function localData(text, callResponse)
                    {
                        callResponse(data_loc);
                    }

                    let  searchControl = new L.Control.Search({
                        sourceData: localData,
                        marker: false
                    });
                    // map.addControl( new L.Control.Search({sourceData: localData, markerLocation:true}));
                    map.addControl(searchControl);
                    let marker1;
                    searchControl.on('search:locationfound', function(e) {
                        // Add a new marker to the map at the search location
                        //marker1 = L.marker(e.latlng).addTo(map);
                        sessionStorage.setItem('shop', e.text);
                        marker1 = L.marker(e.latlng,{icon: redMarker})
                            .bindPopup(search_pop_up_simple(e.text))
                            .addTo(map);
                    });
                }
            });
        });

    }

    search_markers();


    //ΥΠΟΛΟΓΙΖΕΙ ΤΗΝ ΑΠΟΣΤΑΣΗ ΓΙΑ ΤΑ 50m
    function calculate_distance(pointA,pointB) {
        let distance = 0;
        distance = Math.sqrt(Math.pow(pointB[0] - pointA[0], 2) + Math.pow(pointB[1] - pointA[1], 2))
        return distance;

        //console.log(distance);
    }


    $("#shop_filter_form").submit(function(event) {
        event.preventDefault(); // prevent the form from submitting
        var selected_shop = $("#shop_filter").val();
        console.log(selected_shop);

        markersLayer.clearLayers();

        // Send an AJAX request to the server with the selected shop name
        $.ajax({
            type: "POST",
            url: "ajax_to_get_filter_shops.php",
            data: { shop_name: selected_shop },
            success: function(response) {
                // Parse the JSON response from the server
                let locations = JSON.parse(response);
                console.log("FILTER AJAX");
                console.log(locations);

                $.ajax({
                    type: 'GET',
                    url: 'ajax_to_check_filter_markers.php',
                    success: function (data2){
                        let offers_markers_names = JSON.parse(data2);
                        //console.log(offers_markers_names);

                        // let filter_marker_layer = L.layerGroup();
                        // filter_marker_layer.addTo(map);

                        // Iterate through the locations and add markers to the map
                        for (let i = 0; i < locations.length; i++) {
                            let name = locations[i].name;
                            let coordinates = locations[i].coordinates;
                            let lon = coordinates[0];
                            let lat = coordinates[1];

                            let marker2 = L.marker([lat, lon],{title: name});

                            //  ΕΧΕΙ ΠΡΟΣΦΟΡΑ ΤΟ ΚΑΤΑΣΤΗΜΑ
                            if (offers_markers_names.includes(name)) {
                                marker2.setIcon(greenIcon);
                                //console.log("Distance:" + dist);
                                //console.log("\n");

                                // Send an AJAX request to the server using jQuery
                                $.ajax({
                                    type: 'POST',
                                    url: 'ajax_to_print_the_offers_for_markers.php',
                                    data: {shop: name},
                                    success: function(data) {
                                        // Do something with the data returned by the server, such as displaying it in an alert box
                                        console.log("DATA AJAX");
                                        let offers_marker = JSON.parse(data);
                                        console.log(offers_marker);

                                        // Create the content of the popup using the data
                                        var content = '<h1>' + name + '</h1>';
                                        content += '<ul>';
                                        for (var i = 0; i < offers_marker.length; i++) {
                                            content += '<h5>Product: ' + offers_marker[i]['product_name'] + '</h5>';
                                            content += '<li>Username: ' + offers_marker[i]['username'] + '</li>';
                                            content += '<li>Price: ' + offers_marker[i]['price'] + '</li>';
                                            content += '<li>Likes: ' + offers_marker[i]['likes'] + '</li>';
                                            content += '<li>Dislikes: ' + offers_marker[i]['dislikes'] + '</li>';
                                            content += '<li>Stock: ' + offers_marker[i]['stock'] + '</li>';
                                            content += '<li>Offer date: ' + offers_marker[i]['offer_date'] + '</li>';
                                            content += '<li>Expiry date: ' + offers_marker[i]['expiry_date'] + '</li>';
                                            if (offers_marker[i]['criteria'] === 'yes'){
                                                content += '<li>Criteria: '  + "\u2713" + '</li>';
                                            }
                                            else if (offers_marker[i]['criteria'] === 'no'){
                                                content += '<li>Criteria: '  + "x" + '</li>';
                                            }
                                            content += '<br>';
                                        }
                                        content += '</ul>';
                                        marker2.bindPopup(content);
                                        marker2.addTo(markersLayer);
                                    }
                                });
                            }
                            // ΔΕΝ ΕΧΕΙ ΠΡΟΣΦΟΡΑ ΤΟ ΚΑΤΑΣΤΗΜΑ
                            else {
                                marker2.setIcon(greyIcon);
                                // Send an AJAX request to the server using jQuery
                                $.ajax({
                                    type: 'POST',
                                    url: 'ajax_to_print_the_offers_for_markers.php',
                                    data: {shop: name},
                                    success: function(data) {
                                        // Do something with the data returned by the server, such as displaying it in an alert box
                                        console.log("DATA AJAX");
                                        let offers_marker = JSON.parse(data);
                                        console.log(offers_marker);

                                        // Create the content of the popup using the data
                                        var content = '<h1>' + name + '</h1>';
                                        content += '<h3>' + 'ΔΕΝ ΥΠΑΡΧΟΥΝ ΠΡΟΣΦΟΡΕΣ' + '</h3>';
                                        marker2.bindPopup(content);
                                        sessionStorage.setItem('shop', name);
                                        marker2.addTo(markersLayer);
                                    }
                                });
                            }
                        }
                    }
                });
            }
        });
    });


    // Code to update stock of products
    $(".form-submit").click(function(event) {
        event.preventDefault(); // Prevent the form from being submitted

        var product = $("#product").val();
        var shop = $("#shop").val();
        var username = $("#username").val();

        $.ajax({
            type: "POST",
            url: "delete_offer.php",
            data: { product: product, shop: shop, username: username},
            success: function(response) {
                console.log("Data deleted from database");
                alert(response);
                eval_markers();
                location.reload();
            }
        });
        // Clear the product field
        $('#my_form').children("#product").val('');
        $('#my_form').children("#shop").val('');
        $('#my_form').children("#username").val('');
    });

    const targetDiv = document.getElementById("title");
    const btn = document.getElementById("myBtn");
    const rankingsBody = document.querySelector("#rankings > tbody");
    targetDiv.style.display = "none";

    btn.onclick = function () {
        $('#myBtn').html('Hide Leaderboard');
        if (targetDiv.style.display !== "none") {
            targetDiv.style.display = "none";
            $('#myBtn').html('Show Leaderboard');
        } else {
            targetDiv.style.display = "block";
        }
    };

    const targetDiv1 = document.getElementById("delete_offer");
    const btn1 = document.getElementById("mybutton");
    targetDiv1.style.display = "none";

    btn1.onclick = function () {
        if (targetDiv1.style.display !== "none") {
            targetDiv1.style.display = "none";
        } else {
            targetDiv1.style.display = "block";
        }
    };

    const targetDiv2 = document.getElementById("charts");
    const btn2 = document.getElementById("button");
    targetDiv2.style.display = "none";

    btn2.onclick = function () {
        if (targetDiv2.style.display !== "none") {
            targetDiv2.style.display = "none";
        } else {
            targetDiv2.style.display = "block";
        }
    };

    var barGraph;
    $(document).ready(function() {
        // Bind a submit event listener to the form
        $('#form').submit(function(e) {
            // Prevent the form from being submitted
            e.preventDefault();

            // Retrieve the selected year and month from the form
            var year = $('#year').val();
            var month = $('#month').val();
            // console.log(year);
            // console.log(month);

            // Send an HTTP request to the PHP script using Ajax
            $.ajax({
                type: 'POST',
                url: 'ajax_to_get_offers_for_chart.php',
                data: {year: year, month: month},
                success: function(response) { // Callback function to handle the response
                    // Parse the response (which should be a JSON string) into a JavaScript object
                    var chart_data = JSON.parse(response);
                    // console.log(chart_data); // Debugging: log the data to the console
                    // console.log(chart_data.data);

                    var dataArray = Object.values(chart_data.data);
                    // const iszero = (currentValue) => currentValue === 0;
                    // console.log(dataArray);
                    // console.log(dataArray.every(iszero));

                    // Use Chart.js to create a bar chart with the data
                    var x = {
                        labels: chart_data.labels, // Use the labels from the data object
                        datasets: [{
                            label: 'Number of offers',
                            data: dataArray, // Use the data from the data object
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1,
                            type: 'bar',
                            yAxisID: null
                        }]
                    };

                    var options= {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    };

                    // destroy previous chart
                    var meta = barGraph && barGraph.data && barGraph.data.datasets[0]._meta;
                    for (let i in meta) {
                        if (meta[i].controller) meta[i].controller.chart.destroy();
                    }

                    // make new chart
                    var ctx = $("#myChart");
                    barGraph = new Chart(ctx, {
                        type: 'bar',
                        data: x,
                        options: options
                    });
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        });
    });


    function loadRankings () {
        $(function () {
            $.ajax({
                type: 'GET',
                url: 'ajax_leaderboard.php',
                success: function (data) {
                    let res = JSON.parse(data);
                    console.log(res);
                    // Populate Leaderboard

                    res.forEach((row) => {
                        const tr = document.createElement("tr");

                        row.forEach((cell) => {
                            const td = document.createElement("td");
                            td.textContent = cell;
                            tr.appendChild(td);
                        });

                        rankingsBody.appendChild(tr);
                    });

                    const rowsPerPage = 10;
                    const rows = $('#rankings tbody tr');
                    const rowsCount = rows.length;
                    const pageCount = Math.ceil(rowsCount / rowsPerPage); // avoid decimals
                    const numbers = $('#numbers');

                    // Generate the pagination.
                    for (var i = 0; i < pageCount; i++) {
                        numbers.append('<li><a href="#">' + (i+1) + '</a></li>');
                    }

                    // Mark the first page link as active.
                    $('#numbers li:first-child a').addClass('active');

                    // Display the first set of rows.
                    displayRows(1);

                    // On pagination click.
                    $('#numbers li a').click(function(e) {
                        var $this = $(this);

                        e.preventDefault();

                        // Remove the active class from the links.
                        $('#numbers li a').removeClass('active');

                        // Add the active class to the current link.
                        $this.addClass('active');

                        // Show the rows corresponding to the clicked page ID.
                        displayRows($this.text());
                    });

                    // Function that displays rows for a specific page.
                    function displayRows(index) {
                        var start = (index - 1) * rowsPerPage;
                        var end = start + rowsPerPage;

                        // Hide all rows.
                        rows.hide();

                        // Show the proper rows for this page.
                        rows.slice(start, end).show();
                    }
                }
            });
        });
    }

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
        // var shop_name = sessionStorage.getItem('shop');
        $(function (){
            $.ajax({
                type: 'GET',
                url: 'ajax_all_offers.php',
                // data: {shop_name: shop_name},
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
            $.ajax({
                type: 'GET',
                url: 'ajax_offer.php',
                success: function (data){
                    let products = JSON.parse(data);
                    console.log(products);

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
    loadRankings();
    eval_markers();
    search_product();
</script>
</body>
</html>