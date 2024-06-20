<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Câu hỏi</title>
    <link rel="stylesheet" href="../css/styles_admin.css">
</head>
<body>
    <div id="admin-header">
    <h2>Quản lý Câu hỏi</h2>
        <nav>
            <ul>
                <li><a href="index.html">Dashboard</a></li>
                <li><a href="manage_users.php">Quản Lý Người Dùng</a></li>
                <li><a href="manage_questions.php">Quản Lý Câu Hỏi</a></li>
                <li><a href="../user/logout.php">Thoát</a></li>
            </ul>
        </nav>
    </div>
        <div id="admin-content">
            <!-- Hiển thị bảng dữ liệu câu hỏi -->
            <table border="1">
                <tr>
                    <th>ID câu hỏi</th>
                    <th>N.dung câu hỏi</th>
                    <th>Đáp án A</th>
                    <th>Đáp án B</th>
                    <th>Đáp án C</th>
                    <th>Đáp án D</th>
                    <th>Đáp án đúng</th>
                    <th>Độ khó</th>
                    <th>Thao tác</th>
                </tr>
                <?php
                include '../user/conn_db.php';
    
                // Truy vấn cơ sở dữ liệu
                $sql = "SELECT * FROM questions";
                $result = $conn->query($sql);
    
                // Kiểm tra nếu có kết quả
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td style='text-align:center'>".$row['id']."</td>
                            <td>".$row['question']."</td>
                            <td>".$row['option_a']."</td>
                            <td>".$row['option_b']."</td>
                            <td>".$row['option_c']."</td>
                            <td>".$row['option_d']."</td>
                            <td style='text-align:center'>".$row['correct_option']."</td>
                            <td style='text-align:center'>".$row['difficulty']."</td>
                            <td>
                                <a href='#' onclick=\"toggleEditForm('edit_form_".$row['id']."')\">Sửa</a>
                                <a href='delete_question.php?id=".$row['id']."' onclick=\"return confirm('Bạn có chắc chắn muốn xóa câu hỏi này?')\">Xóa</a>
                            </td>
                        </tr>";
                        echo "<tr id='edit_form_".$row['id']."' class='edit-form'>
                            <td colspan='9'>
                                <form action='mn_Question/update_question.php' method='POST'>
                                    <input type='hidden' name='id' value='".$row['id']."'>
                                    Nội dung câu hỏi: <input type='text' name='question' value='".$row['question']."'required><br>
                                    Đáp án A: <input type='text' name='option_a' value='".$row['option_a']."' required><br>
                                    Đáp án B: <input type='text' name='option_b' value='".$row['option_b']."'required><br>
                                    Đáp án C: <input type='text' name='option_c' value='".$row['option_c']."'required><br>
                                    Đáp án D: <input type='text' name='option_d' value='".$row['option_d']."'required><br>
                                    Đáp án đúng: <input type='text' name='correct_option' value='".$row['correct_option']."'required><br>
                                    Độ khó: <input type='text' name='difficulty' value='".$row['difficulty']."'required><br>
                                    <input type='submit' value='Lưu'>
                                </form>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>Không có dữ liệu</td></tr>";
                }
                $conn->close();
                ?>
            </table>
        </div>
        <script>
        function toggleEditForm(formId) {
            var form = document.getElementById(formId);
            if (form.style.display === 'none') {
                form.style.display = 'table-row';
            } else {
                form.style.display = 'none';
            }
        }
        </script>
</body>
</html>