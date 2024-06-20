<?php
include '../../user/conn_db.php';

// Nhận ID người dùng cần xóa
$id = $_GET['id'];

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Bắt đầu giao dịch
$conn->begin_transaction();

try {
    $sql1 = "DELETE FROM game_session WHERE user_id = '$id'";
    $conn->query($sql1);
    $sql2 = "DELETE FROM users WHERE id = '$id'";
    $conn->query($sql2);

    // Xác nhận giao dịch
    $conn->commit();

    echo "<script>alert('Xóa người dùng thành công');</script>";
    echo "<script>window.location.href='../manage_users.php';</script>";
} catch (Exception $e) {
    // Hủy giao dịch nếu có lỗi
    $conn->rollback();

    echo "<script>alert('Lỗi khi xóa người dùng: " . $e->getMessage() . "');</script>";
    echo "<script>window.location.href='../manage_users.php';</script>";
}

// Đóng kết nối
$conn->close();
?>
