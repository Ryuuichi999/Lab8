<?php
    if (isset($_POST['vill_name'])) {
        $vill_name = $_POST['vill_name'];
    } else {
        echo "Vill Name not provided.";
        exit;
    }

    $db = new PDO('pgsql:host=10.199.67.11;port=5432;dbname=sc333302;', 'postgres','postgres');
    $sql = $db->prepare("SELECT gid,vill_code,prov_code,amp_idn,tam_idn,vill_idn,vill_nm_t,ST_AsGeoJSON(geom) as geom FROM village_name_wgs84 WHERE vill_nm_t= :vill_name");
    $params = ["vill_name"=>$vill_name];
    $sql->execute($params);

    echo "<table class='table table-striped'>";
    echo "<tr><th>GID</th><th>หมู่ที่</th><th>ProvinceID</th><th>AmphoeID</th><th>TambonID</th><th>VillageID</th><th>ชื่อหมู่บ้าน</th><th>Geom</th></tr>";

    while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        foreach ($row as $field => $value) {
            if ($field == 'geom') {
                // แปลงค่าของ geom ให้เป็นข้อความ หรือ JSON object
                $value = json_decode($value); // หรือคุณอาจจะใช้วิธีอื่นๆในการแสดงค่าของ geom
                $value = json_encode($value, JSON_UNESCAPED_UNICODE); // แสดงค่าแบบ JSON
            }
            echo "<td>{$value}</td>";
        }
        echo "</tr>";
    }

    echo "</table>";
?>
