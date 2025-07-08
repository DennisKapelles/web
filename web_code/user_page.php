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
    <link rel="stylesheet" type="text/css" href="user_initial_page.css">

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


    <!--                    ==========Jquery==========                   -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>


    <title>User Page</title>

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

<script>

    function getLocation() {
        if (navigator.geolocation)
        {
            navigator.geolocation.getCurrentPosition(showPosition);
        }
        else
        {
            x.innerHTML = "Geolocation is not supported by this browser.";
        }
    }

    function showPosition(position) {
        var map = L.map('mapid').setView([position.coords.latitude,position.coords.longitude], 12);
        let marker = L.marker ([position.coords.latitude,position.coords.longitude],{draggable: "true" });
        marker.addTo(map);
        marker.bindPopup("<b>Current location!</b>").openPopup();
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution:
        '&copy; <a href="http://openstreetmap.org"/OpenStreetMap</a> contributors' +
        ', Tiles courtesy of <a href="https://geo6.be/">GEO-6</a>',
        maxZoom: 18
        }).addTo(map);
        // var circle = L.circle([position.coords.latitude,position.coords.longitude], {
        // color: 'red',
        // fillColor: '#f03',
        // fillOpacity: 0.3,
        // radius: 5000
        // }).addTo(map);
        // marker.on("click", markerClick);
        // function markerClick(event) {
        //     this.getPopup()
        //     .setLatLng(event.latlng)
        //     .setContent("Συντεταγμένες σημείου: " + event.latlng.toString());
        // }
    }
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

    let marker = L.marker([38.2462420, 21.7350847],{draggable:"true", icon: yellowIcon}).addTo(map);
    marker.bindPopup("<b>Current location!</b>").openPopup();

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution:'&copy; <a href="http://openstreetmap.org"/OpenStreetMap</a> contributors' +', Tiles courtesy of <a href="https://geo6.be/">GEO-6</a>',
        maxZoom: 18
    }).addTo(map);

    let markersLayer = new L.LayerGroup();
    map.addLayer(markersLayer);

    //-----marker.on("click", markerClick);
    // Add an event listener to the marker that is triggered when the user drags and releases it

    var circle;
    var current_lat;
    var current_lon;
    marker.on('dragend', function(e) {
        // Get the final position of the marker after the user has dragged and released it
        var finalPosition = e.target.getLatLng();

        // Calculate the current latitude and longitude of the marker
        current_lat = finalPosition.lat;
        current_lon = finalPosition.lng;
        console.log(current_lat,current_lon);

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
                            let dist = calculate_distance([current_lat,current_lon],[lat,lon]);

                            //  ΕΧΕΙ ΠΡΟΣΦΟΡΑ ΤΟ ΚΑΤΑΣΤΗΜΑ
                            if (offers_markers_names.includes(name)) {
                                marker2.setIcon(greenIcon);
                                //console.log("Distance:" + dist);
                                //console.log("\n");
                                if(dist < 0.0005){
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
                                            content += '<button onclick=window.location.href="evaluation_page.php">Evaluate</button>';
                                            content += '<button onclick=window.location.href="make_an_offer.php">Add Offer</button>';
                                            marker2.bindPopup(content);
                                            sessionStorage.setItem('shop', name);
                                            marker2.addTo(markersLayer);
                                        }
                                    });
                                }
                                else {

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
                            }
                            // ΔΕΝ ΕΧΕΙ ΠΡΟΣΦΟΡΑ ΤΟ ΚΑΤΑΣΤΗΜΑ
                            else {
                                marker2.setIcon(greyIcon);
                                if(dist < 0.0005){
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
                                            content += '<button onclick=window.location.href="make_an_offer.php">Add Offer</button>';
                                            marker2.bindPopup(content);
                                            sessionStorage.setItem('shop', name);
                                            marker2.addTo(markersLayer);
                                        }
                                    });
                                }
                                else {
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
                    }
                });
            }
        });


        if (circle !== undefined) {
            map.removeLayer(circle);
        }

        circle = L.circle([current_lat, current_lon], {
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.3,
            radius: 50
        }).addTo(map);

    });


    function search_pop_up_simple(poi){
        return `<div>
                <h4>Όνομα καταστήματος:${poi}</h4>
                <button onclick=window.location.href="make_an_offer.php">Add Offer</button>
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


                        let lat_x = data_loc[i].loc[0];
                        let lon_x = data_loc[i].loc[1];

                        //console.log(data_loc[i].loc);
                        //console.log("Lat_point:" + lat_x);
                        //console.log("Lon_point:" + lon_x);
                        //console.log("\n");

                        let dist = calculate_distance([current_lat,current_lon],[lat_x,lon_x]);
                        console.log("Distance:" + dist);
                        //console.log("\n");
                        if(dist < 0.0005){

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
                                    content += '<button onclick=window.location.href="evaluation_page.php">Evaluate</button>';
                                    content += '<button onclick=window.location.href="make_an_offer.php">Add Offer</button>';
                                    marker1.bindPopup(content);
                                    sessionStorage.setItem('shop', title);
                                    marker1.addTo(markersLayer);
                                }
                            });
                        }
                        else {

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
                            let dist = calculate_distance([current_lat,current_lon],[lat,lon]);

                            //  ΕΧΕΙ ΠΡΟΣΦΟΡΑ ΤΟ ΚΑΤΑΣΤΗΜΑ
                            if (offers_markers_names.includes(name)) {
                                marker2.setIcon(greenIcon);
                                //console.log("Distance:" + dist);
                                //console.log("\n");
                                if(dist < 0.0005){
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
                                            content += '<button onclick=window.location.href="evaluation_page.php">Evaluate</button>';
                                            content += '<button onclick=window.location.href="make_an_offer.php">Add Offer</button>';
                                            marker2.bindPopup(content);
                                            sessionStorage.setItem('shop', name);
                                            marker2.addTo(markersLayer);
                                        }
                                    });
                                }
                                else {

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
                            }
                            // ΔΕΝ ΕΧΕΙ ΠΡΟΣΦΟΡΑ ΤΟ ΚΑΤΑΣΤΗΜΑ
                            else {
                                marker2.setIcon(greyIcon);
                                if(dist < 0.0005){
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
                                            content += '<button onclick=window.location.href="make_an_offer.php">Add Offer</button>';
                                            marker2.bindPopup(content);
                                            sessionStorage.setItem('shop', name);
                                            marker2.addTo(markersLayer);
                                        }
                                    });
                                }
                                else {
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
                                            //sessionStorage.setItem('shop', name);
                                            marker2.addTo(markersLayer);
                                        }
                                    });
                                }
                            }
                        }
                    }
                });
            }
        });
    });

    offers_markers();

</script>

</body>

</html>