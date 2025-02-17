<?php
    if (isset($_POST['gid']) && is_numeric($_POST['gid'])) { // If gid is passed, select by gid
        $gid = $_POST['gid'];
    } else {
        $gid = "-9999";
    }

    // Establishing a database connection
    $db = new PDO('pgsql:host=10.199.67.11;port=5432;dbname=sc333302;', 'postgres', 'postgres');
    
    // Prepare the SELECT query
    $sql = $db->prepare("SELECT gid, vill_code, prov_code, amp_idn, tam_idn, vill_idn, vill_nm_t, ST_X(geom) as longitude, ST_Y(geom) as latitude FROM village_name_wgs84 WHERE gid = :gid");
    $params = ["gid" => $gid];
    
    // Execute the SELECT query
    $sql->execute($params);

    // Fetch the result
    $row = $sql->fetch(PDO::FETCH_ASSOC);

    // Check if a result was returned
    if ($row) {
        // Return the data as a JSON response
        echo json_encode($row);
    } else {
        // Return an error if no data found
        echo "Error: No record found for GID {$gid}.";
    }
?>
