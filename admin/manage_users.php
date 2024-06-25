<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý người dùng</title>
    <link rel="stylesheet" href="../css/styles_admin.css">
</head>
<body>
    <div id="admin-header">
        <h2>Quản lý người dùng</h2>
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
        <!-- Hiển thị bảng dữ liệu người dùng -->
        <table border="1">
            <tr>
                <th>ID User</th>
                <th>Tên tài khoản</th>
                <th>Email</th>
                <th>Mật khẩu(Mã hóa)</th>
                <th>Thao tác</th>
            </tr>
            <?php
            include '../user/conn_db.php';

            // Truy vấn cơ sở dữ liệu
            $sql = "SELECT * FROM users";
            $result = $conn->query($sql);

            // Kiểm tra nếu có kết quả
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td style='text-align:center'>".$row['id']."</td>
                        <td>".$row['username']."</td>
                        <td>".$row['email']."</td>
                        <td>".substr($row['password'],0,15).'...'."</td>
                        <td>
                            <a href='#' class='edit-link' data-form-id='edit_form_".$row['id']."')\">Sửa</a>
                            <a href='mn_Users/delete_user.php?id=".$row['id']."' onclick=\"return confirm('Bạn có chắc chắn muốn xóa người dùng này?')\">Xóa</a>
                        </td>
                    </tr>";
                    echo "<tr id='edit_form_".$row['id']."' class='edit-form' style='display: none;'>
                            <td colspan='5'>
                                <form action='mn_Users/update_user.php' method='POST'>
                                    <input type='hidden' name='id' value='".$row['id']."'>
                                    Tên tài khoản: <input type='text' name='username' value='".$row['username']."'required><br>
                                    Email: <input type='text' name='email' value='".$row['email']."'required><br>
                                    Mật khẩu: <input type='text' name='password' value='".$row['password']."' required><br>
                                    <input type='submit' value='Lưu'>
                                </form>
                            </td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Không có dữ liệu</td></tr>";
            }

            // Đóng kết nối
            $conn->close();
            ?>
        </table>
    </div>
    <script>
        function toggleEditForm(formId) {
            var form = document.getElementById(formId);
            if (form.style.display === 'none'|| form.style.display === '') {
                form.style.display = 'table-row';
            } else {
                form.style.display = 'none';
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            var editLinks = document.querySelectorAll('.edit-link');
            editLinks.forEach(function(link) {
                link.addEventListener('click', function(event) {
                    event.preventDefault();
                    var formId = this.getAttribute('data-form-id');
                    var form = document.getElementById(formId);
                    if (form.style.display === 'none' || form.style.display === '') {
                        form.style.display = 'table-row';
                    } else {
                        form.style.display = 'none';
                    }
                });
            });
        });
        </script>
</body>
</html>
