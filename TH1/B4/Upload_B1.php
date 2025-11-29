<?php
ob_start(); 
session_start();
include 'db.php';

if (isset($_POST['btnAdd'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    
    $target_dir = "images/";
    if (!file_exists($target_dir)) mkdir($target_dir, 0777, true); 
    
    $filename = time() . "_" . basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $filename;
    
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $sql = "INSERT INTO flowers (name, description, image_path) VALUES ('$name', '$desc', '$target_file')";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['msg'] = "Thêm hoa thành công!";
        } else {
            $_SESSION['error'] = "Lỗi CSDL: " . mysqli_error($conn);
        }
    } else {
        $_SESSION['error'] = "Lỗi khi upload ảnh!";
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM flowers WHERE id=$id");
    
    $_SESSION['msg'] = "Đã xóa hoa thành công!";
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Hoa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center text-primary mb-4">Danh sách các loài hoa</h2>

    <?php if (isset($_SESSION['msg'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= $_SESSION['msg'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['msg']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= $_SESSION['error'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="card p-3 mb-4 bg-light shadow-sm">
        <h5 class="card-title">Thêm loài hoa mới</h5>
        <form method="post" enctype="multipart/form-data" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="name" class="form-control" placeholder="Tên hoa" required>
            </div>
            <div class="col-md-4">
                <input type="text" name="description" class="form-control" placeholder="Mô tả ngắn" required>
            </div>
            <div class="col-md-3">
                <input type="file" name="image" class="form-control" accept="image/*" required>
            </div>
            <div class="col-md-1">
                <button type="submit" name="btnAdd" class="btn btn-success w-100">Lưu</button>
            </div>
        </form>
    </div>

    <div class="row">
        <?php
        $result = mysqli_query($conn, "SELECT * FROM flowers ORDER BY id DESC");
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
        ?>
            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow">
                    <img src="<?= $row['image_path'] ?>" class="card-img-top" style="height: 200px; object-fit: cover;" alt="<?= $row['name'] ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= $row['name'] ?></h5>
                        <p class="card-text text-muted"><?= $row['description'] ?></p>
                        <a href="<?= $_SERVER['PHP_SELF'] ?>?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</a>
                    </div>
                </div>
            </div>
        <?php 
            }
        } else {
            echo "<p class='text-center'>Chưa có dữ liệu hoa nào.</p>";
        }
        ?>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php ob_end_flush(); ?>