<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Query เมืองเก่า</title>
    <!-- ✅ เพิ่ม jQuery และ Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" integrity="sha512-jnSuA4Ss2PkkikSOLtYs8BlYIeeIK1h99ty4YfvRPAlzr377vr3CXDb7sb7eEEBYjDtcYj+AjBH3FLv5uSJuXg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <div class="container my-5">
        <!-- ฟอร์มที่มีการส่งข้อมูลไปที่ process_php_db.php -->
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center mb-4">ค้นหาข้อมูลหมู่บ้าน</h2>
                <form id="queryForm" method="post" class="card p-4 shadow-sm">
                    <div class="mb-3">
                        <label for="vill_name" class="form-label">เลือกหมู่บ้าน</label>
                        <select name="vill_name" id="vill_name" class="form-select">
                            <option value="บ้านโนนตุ่น">บ้านโนนตุ่น</option>
                            <option value="บ้านขามเจริญ">บ้านขามเจริญ</option>
                            <option value="บ้านการเคหะ">บ้านการเคหะ</option>
                            <option value="บ้านกุดกว้าง">บ้านกุดกว้าง</option>
                            <option value="บ้านตูมน้อย">บ้านตูมน้อย</option>
                            <option value="บ้านดอนบม">บ้านดอนบม</option>
                            <option value="บ้านฉัตรทอง">บ้านฉัตรทอง</option>
                            <option value="บ้านสะอาด">บ้านสะอาด</option>
                        </select>
                    </div>
                    <!-- ปุ่ม Submit -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">ค้นหา</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ตำแหน่งที่จะแสดงผลลัพธ์ -->
        <div id="mkTable" class="mt-5"></div>
    </div>

    <script>
        $(document).ready(function() {
            // ✅ เมื่อกด Submit
            $("#queryForm").submit(function(e) {
                e.preventDefault(); // ไม่ให้ฟอร์มรีโหลดหน้าใหม่
                var selectedVillage = $("#vill_name").val(); // ดึงค่าหมู่บ้านที่เลือก

                console.log("🔍 ค่า vill_name ที่ถูกส่ง: " + selectedVillage); // ✅ Debug

                $.ajax({
                    url: "process_php_db.php", // ส่งข้อมูลไปที่ process_php_db.php
                    type: "POST",
                    data: {
                        vill_name: selectedVillage
                    }, // ส่งค่า vill_name
                    success: function(response) {
                        $("#mkTable").html(response); // แสดงผลลัพธ์ใน div#mkTable
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: " + status + " - " + error);
                    }
                });
            });
        });
    </script>
</body>

</html>