<?php
    // Check if the gid is passed and is numeric, otherwise set a default value
    if (isset($_POST['gid']) && is_numeric($_POST['gid'])) { 
        $gid = $_POST['gid'];
    } else {
        $gid = "-9999";
    }

    // Check and set default values for other variables if not provided
    $vill_nm_t = isset($_POST['vill_nm_t']) ? $_POST['vill_nm_t'] : "NA";
    $vill_code = isset($_POST['vill_code']) ? $_POST['vill_code'] : "NA";
    $vill_idn = isset($_POST['vill_idn']) ? $_POST['vill_idn'] : "NA";
    $lat = isset($_POST['latitude']) && is_numeric($_POST['latitude']) ? $_POST['latitude'] : "0";
    $lng = isset($_POST['longitude']) && is_numeric($_POST['longitude']) ? $_POST['longitude'] : "0";

    // Database connection
    $db = new PDO('pgsql:host=10.199.67.11;port=5432;dbname=sc333302;', 'postgres', 'postgres');

    // Prepare the UPDATE query
    $sql = $db->prepare("UPDATE village_name_wgs84 SET vill_code=:vc, prov_code=40, amp_idn=4001, tam_idn=400106, vill_idn=:vi, vill_nm_t=:vn, geom=ST_SetSRID(ST_MakePoint(:lng,:lat), 4326) WHERE gid=:gid;");
    
    // Parameters for the query
    $params = [
        "vc" => $vill_code, 
        "vi" => $vill_idn, 
        "vn" => $vill_nm_t, 
        "lng" => $lng, 
        "lat" => $lat, 
        "gid" => $gid
    ];

    // Execute the query
    if ($sql->execute($params)) {
        echo "{$vill_nm_t} successfully updated.";
    } else {
        // Output the error information if query fails
        echo "ERROR !! ";
        var_dump($sql->errorInfo());
    }
?>
