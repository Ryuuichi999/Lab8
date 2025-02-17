<?php
    // ตรวจสอบว่า gid ถูกส่งมาหรือไม่ และเป็นตัวเลข
    if (isset($_POST['gid']) && is_numeric($_POST['gid'])) {
        $gid = $_POST['gid'];
    } else {
        $gid = "-9999"; // ค่าเริ่มต้นในกรณีที่ไม่ได้ส่ง gid
    }

    try {
        // เชื่อมต่อกับฐานข้อมูล PostgreSQL
        $db = new PDO('pgsql:host=10.199.67.11;port=5432;dbname=sc333302;', 'postgres', 'postgres');
        
        // สร้างคำสั่ง DELETE โดยใช้ gid
        $sql = $db->prepare("DELETE FROM village_name_wgs84 WHERE gid = :gid");
        $params = ["gid" => $gid];

        // การ execute คำสั่ง SQL
        $sql->execute($params);

        // ตรวจสอบผลลัพธ์การลบ
        if ($sql->rowCount() > 0) {
            echo "ID {$gid} successfully deleted."; // ถ้าลบสำเร็จ
        } else {
            echo "No records found with ID {$gid}."; // ถ้าไม่มีข้อมูลที่ตรงกับ gid
        }
    } catch (PDOException $e) {
        // ถ้ามีข้อผิดพลาดในการเชื่อมต่อหรือ query
        echo "Error: " . $e->getMessage();
    }
?>
