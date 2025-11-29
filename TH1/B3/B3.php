<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách sinh viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4 text-primary">Danh sách tài khoản sinh viên</h2>
        
        <?php

        $csvFile = '65HTTT_Danh_sach_diem_danh.csv';
        ini_set('auto_detect_line_endings', TRUE);

        if (file_exists($csvFile)) {
            echo '<div class="card shadow-sm">';
            echo '<div class="card-body p-0">';
            echo '<div class="table-responsive">';
            echo '<table class="table table-bordered table-striped table-hover m-0">';
            
            if (($handle = fopen($csvFile, "r")) !== FALSE) {
                
                if (($headers = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    
                    $bom = pack('H*','EFBBBF');
                    $headers[0] = preg_replace("/^$bom/", '', $headers[0]);

                    echo '<thead class="table-dark"><tr>';
                    foreach ($headers as $header) {
                        echo '<th class="text-nowrap">' . htmlspecialchars(trim($header)) . '</th>';
                    }
                    echo '</tr></thead>';
                }

                echo '<tbody>';
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    if (array_filter($data)) {
                        echo '<tr>';
                        foreach ($data as $cell) {
                            echo '<td>' . htmlspecialchars(trim($cell)) . '</td>';
                        }
                        echo '</tr>';
                    }
                }
                echo '</tbody>';

                fclose($handle);
            }
            
            echo '</table>';
            echo '</div>'; 
            echo '</div>'; 
            echo '</div>'; 
        } else {
            echo '<div class="alert alert-danger text-center">
                    <i class="bi bi-exclamation-triangle-fill"></i> 
                    Lỗi: Không tìm thấy file <strong>' . $csvFile . '</strong>.<br>
                    Vui lòng kiểm tra xem file .csv đã được copy vào cùng thư mục với file code này chưa.
                  </div>';
        }
        ?>
        
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>