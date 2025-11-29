<?php

$mode = isset($_GET['mode']) ? $_GET['mode'] : "user";

$flowers = [
    [
        "name"  => "Hoa Đỗ Quyên",
        "desc"  => "Loài hoa thanh khiết, nhẹ nhàng.",
        "image" => "images/doquyen.jpg"
    ],
    [
        "name"  => "Hoa Hải Dương",
        "desc"  => "Biểu tượng của tình yêu và sự lãng mạn.",
        "image" => "images/haiduong.jpg"
    ],
    [
        "name"  => "Hoa Mai",
        "desc"  => "Hoa Mai sang trọng, quý phái.",
        "image" => "images/mai.jpg"
    ],
      [
        "name"  => "Hoa Tường Vy",
        "desc"  => "Hoa tường vi là loài hoa mang ý nghĩa cát tường.",
        "image" => "images/tuongvy.jpg"
    ]   
];

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách Hoa</title>

    <style>
        body { font-family: Arial; margin: 20px; }

        /* USER MODE */
        .grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
        .card {
            border: 1px solid #ccc;
            border-radius: 6px;
            padding: 15px;
        }
        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        /* ADMIN */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table, th, td {
            border: 1px solid #888;
            padding: 10px;
        }
        table img {
            width: 80px;
            border-radius: 4px;
        }

        form input, textarea {
            width: 100%;
            padding: 8px;
            margin: 8px 0;
        }
    </style>
</head>
<body>

<h2>Danh sách các loài hoa</h2>

<?php if ($mode == "user") { ?>


    <div class="grid">
        <?php foreach ($flowers as $flower) { ?>
            <div class="card">
                <img src="<?= $flower['image'] ?>" alt="">
                <h3><?= $flower['name'] ?></h3>
                <p><?= $flower['desc'] ?></p>
            </div>
        <?php } ?>
    </div>

    <p><a href="?mode=admin">Chuyển sang chế độ Admin</a></p>

<?php } else { ?>

    <table>
        <tr>
            <th>STT</th>
            <th>Tên hoa</th>
            <th>Mô tả</th>
            <th>Ảnh</th>
            <th>Thao tác</th>
        </tr>

        <?php foreach ($flowers as $i => $flower) { ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= $flower['name'] ?></td>
                <td><?= $flower['desc'] ?></td>
                <td><img src="<?= $flower['image'] ?>"></td>
                <td>
                    <button>Sửa</button>
                    <button>Xóa</button>
                </td>
            </tr>
        <?php } ?>
    </table>

    <h3>Thêm / Sửa Hoa</h3>

    <form method="post">
        <label>Tên Hoa:</label>
        <input type="text" name="name">

        <label>Mô tả:</label>
        <textarea name="desc"></textarea>

        <label>Ảnh (images/...):</label>
        <input type="text" name="image" placeholder="images/ten-anh.jpg">

        <button type="submit">Lưu</button>
    </form>

    <p><a href="?mode=user">Chuyển sang chế độ User</a></p>

<?php } ?>

</body>
</html>
