<?php
    $vill_name = $_POST['vill_name'];
    $db = new PDO('pgsql:host=10.199.67.11;port=5432;dbname=sc333302;','postgres','postgres');
    $sql = $db->prepare("SELECT gid,vill_code,prov_code,amp_idn,tam_idn,vill_idn,vill_nm_t,ST_AsGeoJSON(geom) as geom FROM village_name_wgs84 WHERE vill_nm_t= :vill_name");
    $params = ["vill_name"=>$vill_name];
    
    $sql->execute($params);

    $features = []; //Collection of Features
    while($row = $sql->fetch(PDO::FETCH_ASSOC)){
        $feature=['type'=>'Feature'];
        $feature['geometry']=json_decode($row['geom']);
        unset($row['geom']); // remove geom from associative array
        $feature['properties']=$row; 
        array_push($features, $feature);
    }
    $featureCollection=['type'=>'FeatureCollection','features'=>$features]; //features lower case
    echo json_encode($featureCollection); //return geoJson
?>
