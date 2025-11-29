<?php
ob_start(); 
session_start(); 
include 'db.php';

if (isset($_POST['btnUpload']) && isset($_FILES['txtFile'])) {
    $content = file_get_contents($_FILES['txtFile']['tmp_name']);
    $blocks = preg_split("/\n\s*\n/", trim($content));

    $count = 0;
    foreach ($blocks as $block) {
        $lines = explode("\n", trim($block));
        $question = mysqli_real_escape_string($conn, array_shift($lines)); 
        
        $opts = [];
        $ans = "";

        foreach ($lines as $line) {
            if (preg_match("/^([A-D])\. (.*)$/", trim($line), $m)) {
                $opts[$m[1]] = mysqli_real_escape_string($conn, $m[2]);
            }
            if (preg_match("/ANSWER:\s*([A-D])/", trim($line), $m)) {
                $ans = $m[1];
            }
        }

        if (!empty($question) && count($opts) >= 4 && !empty($ans)) {
            $sql = "INSERT INTO questions (question_content, option_a, option_b, option_c, option_d, correct_answer) 
                    VALUES ('$question', '{$opts['A']}', '{$opts['B']}', '{$opts['C']}', '{$opts['D']}', '$ans')";
            mysqli_query($conn, $sql);
            $count++;
        }
    }
    $_SESSION['msg'] = "Đã import thành công $count câu hỏi!";
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

if (isset($_POST['submitQuiz'])) {
    $score = 0;
    $total = 0;
    $result = mysqli_query($conn, "SELECT id, correct_answer FROM questions");
    while ($row = mysqli_fetch_assoc($result)) {
        $total++;
        if (isset($_POST["q{$row['id']}"]) && $_POST["q{$row['id']}"] == $row['correct_answer']) {
            $score++;
        }
    }
    
    $_SESSION['ket_qua'] = [
        'score' => $score,
        'total' => $total
    ];

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bài 2: Trắc nghiệm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5 mb-5">
    <h2 class="text-center text-danger">Bài thi Trắc nghiệm Online</h2>
    
    <?php if (isset($_SESSION['msg'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= $_SESSION['msg'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['msg']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['ket_qua'])): ?>
        <div class="alert alert-info text-center shadow">
            <h3>Kết quả: <?= $_SESSION['ket_qua']['score'] ?> / <?= $_SESSION['ket_qua']['total'] ?> điểm</h3>
            <script>alert('Bạn làm đúng <?= $_SESSION['ket_qua']['score'] ?> / <?= $_SESSION['ket_qua']['total'] ?> câu!');</script>
        </div>
        <?php unset($_SESSION['ket_qua']); // Xóa kết quả để F5 lần sau không hiện lại ?>
    <?php endif; ?>

    <div class="card p-3 mb-4 bg-light">
        <form method="post" enctype="multipart/form-data">
            <label class="fw-bold">Upload đề thi (.txt):</label>
            <div class="input-group mt-2">
                <input type="file" name="txtFile" class="form-control" accept=".txt" required>
                <button type="submit" name="btnUpload" class="btn btn-primary">Tải lên DB</button>
            </div>
        </form>
    </div>

    <form method="post">
        <?php
        $result = mysqli_query($conn, "SELECT * FROM questions");
        if (mysqli_num_rows($result) > 0) {
            $i = 0;
            while ($row = mysqli_fetch_assoc($result)) {
                $i++;
                echo "<div class='card mb-3 shadow-sm'>";
                echo "<div class='card-header fw-bold'>Câu $i: {$row['question_content']}</div>";
                echo "<div class='card-body'>";
                $options = ['A' => $row['option_a'], 'B' => $row['option_b'], 'C' => $row['option_c'], 'D' => $row['option_d']];
                foreach ($options as $key => $val) {
                    echo "<div class='form-check'>";
                    echo "<input class='form-check-input' type='radio' name='q{$row['id']}' value='$key' id='q{$row['id']}$key'>";
                    echo "<label class='form-check-label' for='q{$row['id']}$key'><b>$key.</b> $val</label>";
                    echo "</div>";
                }
                echo "</div></div>";
            }
            echo "<button type='submit' name='submitQuiz' class='btn btn-success btn-lg'>Nộp bài</button>";
        } else {
            echo "<p class='text-center'>Chưa có câu hỏi nào trong CSDL.</p>";
        }
        ?>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php ob_end_flush(); ?>