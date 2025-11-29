<?php
ob_start(); 
session_start();
include 'db.php';

if (isset($_POST['btnUpload']) && isset($_FILES['csvFile'])) {
    $file = $_FILES['csvFile']['tmp_name'];
    
    ini_set('auto_detect_line_endings', TRUE);

    if ($_FILES['csvFile']['size'] > 0) {
        $handle = fopen($file, "r");
        
        fgetcsv($handle); 

        $count = 0;
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if (array_filter($data)) {
                $username  = mysqli_real_escape_string($conn, $data[0]);
                $password  = mysqli_real_escape_string($conn, $data[1]);
                $lastname  = mysqli_real_escape_string($conn, $data[2]);
                $firstname = mysqli_real_escape_string($conn, $data[3]);
                $city      = mysqli_real_escape_string($conn, $data[4]);
                $email     = mysqli_real_escape_string($conn, $data[5]);
                $course    = mysqli_real_escape_string($conn, $data[6]);

                $sql = "INSERT INTO tai_khoan (username, password, lastname, firstname, city, email, course1) 
                        VALUES ('$username', '$password', '$lastname', '$firstname', '$city', '$email', '$course')";
                mysqli_query($conn, $sql);
                $count++;
            }
        }
        fclose($handle);

        $_SESSION['msg'] = "Đã import thành công $count sinh viên!";
        
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bài 3: Danh sách sinh viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center text-success mb-4">Danh sách Tài khoản Sinh viên (DB: CSE48_TH1)</h2>
    
    <?php if (isset($_SESSION['msg'])): ?>
        <div class="alert alert-success alert-dismissible fade show text-center shadow-sm">
            <?= $_SESSION['msg'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['msg']); // Xóa thông báo sau khi đã hiện (F5 lần sau sẽ mất) ?>
    <?php endif; ?>

    <div class="row justify-content-center mb-4">
        <div class="col-md-8">
            <div class="card p-3 bg-light">
                <form method="post" enctype="multipart/form-data" class="d-flex gap-2">
                    <input type="file" name="csvFile" class="form-control" accept=".csv" required>
                    <button type="submit" name="btnUpload" class="btn btn-primary">Import CSV</button>
                </form>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Username</th>
                    <th>Họ và tên lót</th>
                    <th>Tên</th>
                    <th>Thành phố</th>
                    <th>Email</th>
                    <th>Khóa học</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Lấy dữ liệu từ CSDL ra hiển thị
                $result = mysqli_query($conn, "SELECT * FROM tai_khoan");
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>{$row['username']}</td>";
                        echo "<td>{$row['lastname']}</td>";
                        echo "<td>{$row['firstname']}</td>";
                        echo "<td>{$row['city']}</td>";
                        echo "<td>{$row['email']}</td>";
                        echo "<td>{$row['course1']}</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>Chưa có dữ liệu. Vui lòng import file CSV.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php ob_end_flush(); ?>