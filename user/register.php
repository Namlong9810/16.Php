<?php
include 'conn_db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];
    $email = $_POST['email'];

    $check_username_sql = "SELECT id FROM users WHERE username = ?";
    $check_stmt = $conn->prepare($check_username_sql);
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        echo "<script>alert('Tên tài khoản đã tồn tại!!'); window.location.href='../register.html';</script>";
        $check_stmt->close();
        exit;
    }

    if ($password == $confirm_password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $hashed_password, $email);

        if ($stmt->execute()) {
            echo "<script>alert('Đăng kí thành công!!');window.location.href='../login.html'</script>";
            exit;
        } else {
            echo "Lỗi: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "<script>alert('Mật khẩu không khớp!!');window.location.href='../register.html'</script>";
        exit;
    }
}

$conn->close();
?>
