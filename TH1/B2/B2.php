<?php
$data = file_get_contents("Quiz.txt");

$blocks = preg_split("/\n\s*\n/", trim($data));

$questions = [];

foreach ($blocks as $block) {
    $lines = explode("\n", trim($block));

    // Dòng đầu tiên là câu hỏi
    $questionText = array_shift($lines);

    // 4 dòng tiếp theo là lựa chọn A-D
    $options = [];
    foreach ($lines as $line) {
        if (preg_match("/^(A|B|C|D)\. (.*)$/", trim($line), $m)) {
            $options[$m[1]] = $m[2];
        }
    }

    // Lấy đáp án đúng
    if (preg_match("/ANSWER:\s*(.+)/", end($lines), $ans)) {
        $answer = trim($ans[1]);
    } else {
        $answer = "";
    }

    // Lưu vào mảng
    $questions[] = [
        "question" => $questionText,
        "options"  => $options,
        "answer"   => $answer
    ];
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Bài thi trắc nghiệm</title>

<style>
    body {
        font-family: Arial;
        margin: 20px auto;
        max-width: 800px;
        line-height: 1.6;
        background: #f8f9fa;
    }

    h2 {
        text-align: center;
        color: #333;
        margin-bottom: 25px;
    }

    .question-box {
        background: #fff;
        border: 1px solid #dcdcdc;
        padding: 18px;
        border-radius: 10px;
        margin-bottom: 22px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    .question-title {
        font-weight: bold;
        margin-bottom: 12px;
        font-size: 17px;
        color: #222;
    }

    label {
        font-size: 15px;
        cursor: pointer;
    }

    input[type="radio"] {
        transform: scale(1.2);
        margin-right: 6px;
    }

    button {
        display: block;
        margin: 0 auto;
        padding: 12px 24px;
        font-size: 16px;
        border: none;
        background: #007bff;
        color: white;
        border-radius: 8px;
        cursor: pointer;
    }

    button:hover {
        background: #0056b3;
    }

    .result-box {
        background: #fff;
        border: 1px solid #ccc;
        padding: 15px;
        border-radius: 10px;
        margin-top: 25px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    .correct {
        color: green;
        font-weight: bold;
    }

    .wrong {
        color: red;
        font-weight: bold;
    }

    .score {
        font-size: 20px;
        padding-top: 10px;
        text-align: center;
        color: #222;
    }
</style>
</head>

<body>

<h2>BÀI THI TRẮC NGHIỆM ANDROID</h2>

<form method="post">

<?php foreach ($questions as $i => $q): ?>
    <div class="question-box">
        <div class="question-title">
            Câu <?= $i + 1 ?>: <?= $q["question"] ?>
        </div>

        <?php foreach ($q["options"] as $key => $value): ?>
            <label>
                <input type="radio" name="q<?= $i ?>" value="<?= $key ?>">
                <b><?= $key ?>.</b> <?= $value ?>
            </label>
            <br>
        <?php endforeach; ?>
    </div>
<?php endforeach; ?>

<button type="submit">Nộp bài</button>
</form>

<?php
// XỬ LÝ KẾT QUẢ
if ($_POST) {
    echo "<div class='result-box'>";
    echo "<h3>KẾT QUẢ:</h3>";

    $score = 0;

    foreach ($questions as $i => $q) {
        $user = isset($_POST["q$i"]) ? $_POST["q$i"] : "Không chọn";
        $correct = $q["answer"];

        echo "<p><b>Câu " . ($i+1) . ":</b> ";

        if ($user === $correct) {
            echo "<span class='correct'>Đúng ✔</span></p>";
            $score++;
        } else {
            echo "<span class='wrong'>Sai ✘ (Đáp án đúng: $correct)</span></p>";
        }
    }

    echo "<div class='score'>🎯 Điểm của bạn: <b>$score / " . count($questions) . "</b></div>";
    echo "</div>";
}
?>

</body>
</html>
