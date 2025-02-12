process_php_db <?php
    echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" integrity="sha512-jnSuA4Ss2PkkikSOLtYs8BlYIeeIK1h99ty4YfvRPAlzr377vr3CXDb7sb7eEEBYjDtcYj+AjBH3FLv5uSJuXg==" crossorigin="anonymous" referrerpolicy="no-referrer" />';

    $vill_name = $_POST['vill_name'];
    $db = new PDO('pgsql:host=10.199.67.11;port=5432;dbname=sc333302;','postgres','postgres');
    $sql = $db->prepare("SELECT gid,vill_code,prov_code,amp_idn,tam_idn,vill_idn,vill_nm_t,ST_AsGeoJSON(ST_Transform(geom,4326),5) as geom FROM vi_mkvillagept WHERE vill_nm_t= :vill_name");
    $params = ["vill_name"=>$vill_name];
    echo $params["vill_name"];
    $sql->execute($params);
    
    echo "<table class='table table-striped'>";
    echo "<tr><th>GID</th><th>หมู่ที่</th><th>ProvinceID</th><th>AmphoeID</th><th>TambonID</th><th>VillageID</th><th>ชื่อหมู่บ้าน</th><th>Geom</th></tr>";
    
    while($row = $sql->fetch(PDO::FETCH_ASSOC)){
        echo "<tr>";
        foreach($row as $field=>$value){
            echo "<td>{$value}</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
?>