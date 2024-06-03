<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "millionaire";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Chuyển hướng đến trang chính sau khi đăng nhập thành công
            echo "<script>alert('Đăng nhập thành công!'); window.location.href='../login.html';</script>";
        } else {
            // Thông báo và chuyển hướng đến trang đăng nhập
            echo "<script>alert('Mật khẩu không đúng!'); window.location.href='../login.html';</script>";
            
            header("Location: ../login.html");
        }
    } else {
        // Thông báo và chuyển hướng đến trang đăng nhập
        echo "<script>alert('Tên người dùng không tồn tại!'); window.location.href='../login.html';</script>";
    }

    $stmt->close();
}

$conn->close();
?>