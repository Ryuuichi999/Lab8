<?php
    $vill_idn = "NA";
    $vill_code = "NA";
    $vill_nm_t = "NA";
    $latitude = 0;
    $longitude = 0;

    if(isset($_POST['vill_idn'])){
        $vill_idn = $_POST['vill_idn'];
    }
    if(isset($_POST['vill_code'])){
        $vill_code = $_POST['vill_code'];
    }
    if(isset($_POST['vill_nm_t'])){
        $vill_nm_t = $_POST['vill_nm_t'];
    }
    if(isset($_POST['latitude'])){
        $latitude = $_POST['latitude'];
    }
    if(isset($_POST['longitude'])){
        $longitude = $_POST['longitude'];
    }
            
    // Connect to Database
    $db = new PDO('pgsql:host=10.199.67.11;port=5432;dbname=sc333302;', 'postgres','postgres');
    $sql = $db->prepare("INSERT INTO village_name_wgs84 (vill_code,prov_code,amp_idn,tam_idn,vill_idn,vill_nm_t, geom) VALUES (:vc,40,4001,400106,:vi,:vn,ST_SetSRID(ST_MakePoint(:lng,:lat),4326))");
    $params = ["vc"=>$vill_code, "vi"=>$vill_idn, "vn"=>$vill_nm_t, "lng"=>$longitude, "lat"=>$latitude];

    //echo $params["vill_name"];
    //$sql->execute($params);
    
    if ($sql->execute($params)){
        echo "Added {$vill_nm_t} successfully !!";
    } else {
        echo var_dump($sql->errorInfo());
    }
?>