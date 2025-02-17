<?php
    $db = new PDO('pgsql:host=10.199.67.11;port=5432;dbname=sc333302;', 'postgres','postgres');
    $sql = $db->prepare('SELECT DISTINCT vill_nm_t FROM village_name_wgs84');
    $sql->execute();
    $row = $sql->fetch(PDO::FETCH_ASSOC);

    while($row = $sql->fetch(PDO::FETCH_ASSOC)){
        foreach($row as $field=>$value){
            echo "<option value='{$value}'> {$value} </option>";
        }
    }
?>