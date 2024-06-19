<?php
ini_set('display_errors', 0); // Không hiển thị lỗi
ini_set('log_errors', 1); // Ghi lỗi vào log
error_reporting(E_ALL); // Báo cáo mọi lỗi

include 'conn_db.php';
session_start();

// Bắt đầu bộ đệm đầu ra
ob_start();

// Đặt tiêu đề Content-Type cho phản hồi là JSON
header('Content-Type: application/json');

$response = array();

try {
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];

        $stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows>0){
            $user = $result->fetch_assoc();

            $response['username'] = $user['username'];
        } else {
            $response['error'] = 'User not found';
        }
    } else {
        $response['error'] = 'User not logged in';
    }
} catch (Exception $e) {
    // Bắt các ngoại lệ và thêm thông tin lỗi vào phản hồi
    $response['error'] = 'An error occurred: ' . $e->getMessage();
}

// Xóa bộ đệm đầu ra trước khi in JSON
ob_end_clean();

// Trả về phản hồi JSON
echo json_encode($response);

// Kết thúc kịch bản
exit();
?>
