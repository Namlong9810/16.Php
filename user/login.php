<?php
include 'conn_db.php';
session_start();

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
            $_SESSION['user_id'] = $row['id'];

            $user_id = $row['id'];
            $sql = "SELECT id FROM game_session WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows == 0) {
                $sql = "INSERT INTO game_session (user_id, all_answered_Questions, use_help_50_50, use_help_wise_man, use_help_companion, high_score) VALUES (?, 1, 0, 0, 0, 0)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $user_id);
                if ($stmt->execute()) {
                    $new_session_id = $conn->insert_id;
                    $_SESSION['session_id'] = $new_session_id;
                    echo "<script>alert('Đăng nhập thành công! Phiên chơi mới đã được tạo với ID: $new_session_id'); window.location.href='../Index.html';</script>";
                } else {
                    echo "<script>alert('Đăng nhập thành công, nhưng không thể tạo phiên chơi mới!'); window.location.href='../login.html';</script>";
                }
            } else {
                $stmt->bind_result($session_id);
                $stmt->fetch();
                $_SESSION['session_id'] = $session_id;
                echo "<script>alert('Đăng nhập thành công! user_id: {$_SESSION['user_id']}, session_id: {$_SESSION['session_id']}'); window.location.href='../Index.html';</script>";
            }
        } else {
            echo "<script>alert('Mật khẩu không đúng!'); window.location.href='../login.html';</script>";
        }
    } else {
        echo "<script>alert('Tên người dùng không tồn tại!'); window.location.href='../login.html';</script>";
    }

    $stmt->close();
}

$conn->close();
?>
