<?php
include '../../user/conn_db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "UPDATE users SET username = '$username',
            password = '$hashed_password', email = '$email'
            WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Cập nhật thông tin thành công');</script>";
        echo "<script>window.location.href='../manage_users.php';</script>";
    } else {
        echo "<script>alert('Lỗi cập nhật: " . $conn->error . "');</script>";
        echo "<script>window.location.href='../manage_users.php';</script>";
    }
}

$conn->close();
?>