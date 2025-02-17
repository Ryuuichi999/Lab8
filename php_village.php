<?php
foreach ($_POST as $key => $val) {
    echo "Key: {$key} = {$val} <br>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Query เมืองเก่า</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" integrity="sha512-h9FcoyWjHcOcmEVkxOfTLnmZFWIH0iZhZT1H2TbOq55xssQGEJHEaIm+PgoUaZbRvQTNTluNOEfb1ZRy6D3BOw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src = "https://code.jquery.com/jquery-3.4.1.min.js"> </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js" integrity="sha512-BwHfrr4c9kmRkLw6iXFdzcdWV/PGkVgiIyIWLLlTSXzWQzxuSg4DiQUCpauz/EWjgk5TYQqX/kvn9pG1NpYfqg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <style>
        .modal {
            display: none;
            z-index: 1000;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            padding: 20px;
            margin-top: 10%;
            background-color: tan;
        }
    </style>

</head>

<body>
    <form method="post" action="">
        <select name="vill_name" id="vill_name">
            <option value="บ้านโนนตุ่น">บ้านโนนตุ่น</option>
            <option value="บ้านขามเจริญ">บ้านขามเจริญ</option>
            <option value="บ้านการเคหะ">บ้านการเคหะ</option>
            <option value="บ้านกุดกว้าง">บ้านกุดกว้าง</option>
            <option value="บ้านตูมน้อย">บ้านตูมน้อย</option>
            <option value="บ้านดอนบม">บ้านดอนบม</option>
            <option value="บ้านฉัตรทอง">บ้านฉัตรทอง</option>
            <option value="บ้านสะอาด">บ้านสะอาด</option>
        </select>
    </form>

    <div id="map" style=" height:60vh"></div><br>
    <div id="mkTable"> </div>

    <!-- Popup for entering new village -->
    <div id="dlgAddVillage" class="modal">
        <div class="modal-content col-md-7 col-md-offset-4">
            <!-- Form input -->
            <p>Object ID: <span id="obj_id"> New ID </span> </p>
            Village idn: <input type="text" id="vill_idn"><br>
            Village code: <input type="text" id="vill_code"><br>
            Village Name: <input type="text" id="vill_nm_t"><br>
            Latitude: <input type="text" id="latitude"><br>
            Longitude: <input type="text" id="longitude"><br>

            <div id="addButtons"> <!-- Added Div for grouping Buttons in different scenarios -->
                <button id="btnSave" class="btn btn-success">Save</button>
                <button id="btnCancel" class="btn btn-danger btnCancel">Cancel</button> <!-- added btnCancel class -->
            </div>

            <div id="editButtons"> <!-- Added Div for grouping Buttons in different scenarios -->
                <button id="btnUpdate" class="btn btn-success">Update</button>
                <button id="btnDelete" class="btn btn-warning">Delete</button>
                <button id="btnClose" class="btn btn-danger btnCancel">Close</button>
            </div>


        </div>
    </div>

    <script>
        var queryLayer; // The queried layer
        // Load village list from database
        $(function() { // Wait until page is loaded
            // ส่วนของ Load ชื่อหมูบ้าน
            reload_list();
        });

        // Create Map Object
        var map = L.map('map');
        map.setView([16.3901212, 102.8085706], 13);

        // Loading Basemap
        var OpenTopoMap = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
            maxZoom: 17,
            attribution: 'Map data: &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, <a href="http://viewfinderpanoramas.org">SRTM</a> | Map style: &copy; <a href="https://opentopomap.org">OpenTopoMap</a> (<a href="https://creativecommons.org/licenses/by-sa/3.0/">CC-BY-SA</a>)'
        }).addTo(map);
        var Stadia_AlidadeSmoothDark = L.tileLayer('https://tiles.stadiamaps.com/tiles/alidade_smooth_dark/{z}/{x}/{y}{r}.png', {
            maxZoom: 20,
            attribution: '&copy; <a href="https://stadiamaps.com/">Stadia Maps</a>, &copy; <a href="https://openmaptiles.org/">OpenMapTiles</a> &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors'
        });
        var basemap = {
            "Topo Map": OpenTopoMap,
            "Dark": Stadia_AlidadeSmoothDark
        };

        // Event handler when change village
        $("#vill_name").on("change", function() { // On village change event

            // ส่วนของแผนที่
            $.ajax({
                url: 'process_php_db.php', // Target php url
                type: 'POST', // GET or POST
                data: {
                    vill_name: $("#vill_name").val()
                }, // POST variable
                success: function(response) { // Callback function
                    console.log(response);
                    if (queryLayer) { // Remove previous queryLayer
                        map.removeLayer(queryLayer);
                    }
                    var objResponse = JSON.parse(response); //JSON to object
                    console.log(objResponse);
                    queryLayer = L.geoJSON(objResponse, {
                        onEachFeature: function(feature, layer) {

                            // Add Tooltip
                            layer.bindTooltip("หมู่ที่ " + feature.properties.vill_code + " " + feature.properties.vill_nm_t);

                            //Adding popup with info and edit button
                            var strPopup = "<h4>" + feature.properties.vill_nm_t + "</h4><hr>";
                            strPopup += "<br><button id='btnEdit' class='btn btn-primary center-block' onclick='editVillage(" + feature.properties.gid + ")'>Edit</button>";
                            layer.bindPopup(strPopup);
                        }
                    }); //Create Leaflet Layer  

                    map.addLayer(queryLayer); // Add Layer to myMap
                    map.fitBounds(queryLayer.getBounds()); // Zoom to Layer
                } // succuess      
            }); // ajax

            // ส่วนของ Load Table
            $.ajax({
                url: 'process.php',
                type: 'POST', // GET or POST
                data: {
                    vill_name: $("#vill_name").val()
                }, // POST variables
                success: function(response) { //callback function
                    $("#mkTable").html(response);
                }
            });
        });

        // Event Handler
        // Add onClick event on mymap
        map.on('click', function(e) {
            $("#dlgAddVillage").show();
            $("#addButtons").show();
            $("#editButtons").hide();
            $("#latitude").val(e.latlng.lat.toFixed(5));
            $("#longitude").val(e.latlng.lng.toFixed(5));
            //    $("#idDisplay").html("New");
        });

        // Cancel
        $("#btnCancel").on("click", function() {
            $("#dlgAddVillage").hide();
        });

        // Close
        $("#btnClose").on("click", function() {
            $("#dlgAddVillage").hide();
        });

        // Save
        $("#btnSave").on("click", function() {
            // ส่วนของ Add Table
            $.ajax({
                url: 'add_village.php',
                type: 'POST', // GET or POST
                data: {
                    vill_idn: $("#vill_idn").val(),
                    vill_code: $("#vill_code").val(),
                    vill_nm_t: $("#vill_nm_t").val(),
                    latitude: $("#latitude").val(),
                    longitude: $("#longitude").val(),
                }, // POST variables
                success: function(response) { //callback function
                    console.log(response);
                    alert(response);
                    setTimeout(1000); // wait 1 วินาที 
                    reload_list();
                    //$("#mkTable").html(response);
                }
            });

            $("#dlgAddVillage").hide();
        });

        // Delete
        $("#btnDelete").on("click", function() {
            // ส่วนของ Add Table
            $.ajax({
                url: 'delete_village.php',
                type: 'POST', // GET or POST
                data: {
                    gid: $("#obj_id").html(),
                }, // POST variables
                success: function(response) { //callback function
                    console.log(response);
                    alert(response);
                    setTimeout(1000); // wait 1 วินาที 
                    reload_list();
                }
            }); // ajax

            $("#dlgAddVillage").hide();
        });

        // Update
        $("#btnUpdate").on("click", function() {
            // ส่วนของ Add Table
            $.ajax({
                url: 'update_village.php',
                type: 'POST', // GET or POST
                data: {
                    gid: $("#obj_id").html(),
                    vill_idn: $("#vill_idn").val(),
                    vill_code: $("#vill_code").val(),
                    vill_nm_t: $("#vill_nm_t").val(),
                    latitude: $("#latitude").val(),
                    longitude: $("#longitude").val(),
                }, // POST variables
                success: function(response) { //callback function
                    console.log(response);
                    alert(response);
                    setTimeout(1000); // wait 1 วินาที 
                    reload_list();
                    //$("#mkTable").html(response);
                }
            });

            $("#dlgAddVillage").hide();
        });

        /////////////////////////////////////////////////////////////////////
        /// Function ///

        // Function Reload Drop List
        function reload_list() {
            $.ajax({
                url: 'load_village.php',
                success: function(response) { //callback function
                    console.log(response)
                    $("#vill_name").html(response);
                }
            });
        }

        function editVillage(myid) {
            $("#dlgAddVillage").show();
            $("#addButtons").hide();
            $("#editButtons").show();

            $.ajax({
                url: "find_village.php",
                type: "POST",
                data: {
                    gid: myid
                }, // passing id  as parameter to server
                success: function(response) {
                    objVillage = JSON.parse(response);
                    console.log(objVillage);
                    $("#obj_id").html(objVillage.gid);
                    $("#vill_idn").val(objVillage.vill_idn);
                    $("#vill_code").val(objVillage.vill_code);
                    $("#vill_nm_t").val(objVillage.vill_nm_t);
                    $("#latitude").val(objVillage.latitude);
                    $("#longitude").val(objVillage.longitude);
                } //success
            }); // ajax  
        }
    </script>
</body>

</html>