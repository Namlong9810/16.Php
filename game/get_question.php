<?php
include '../user/conn_db.php';
session_start();

$response = array();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['session_id'])) {
    echo json_encode(['error' => 'Bạn cần đăng nhập để chơi.']);
    exit();
}

$user_id = $_SESSION['user_id'];
$session_id = $_SESSION['session_id'];



// Hàm để xác định mức độ câu hỏi dựa trên số lượng câu hỏi đã được trả lời
function determineDifficulty($answeredQuestions) {
    if ($answeredQuestions < 5) {
        return '1';
    } elseif ($answeredQuestions < 10) {
        return '2';
    } else {
        return '3';
    }
}

// Lấy số lượng câu hỏi đã được trả lời từ phiên chơi
$sql = "SELECT all_answered_Questions, high_score FROM game_session WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $session_id);
$stmt->execute();
$result = $stmt->get_result();
$game_session = $result->fetch_assoc();

$answeredQuestions = isset($_GET['answered_questions']) ? intval($_GET['answered_questions']) : 0;

// Xác định mức độ câu hỏi dựa trên số lượng câu hỏi đã được trả lời
$difficulty = determineDifficulty($answeredQuestions);

// Truy vấn câu hỏi dựa trên mức độ và sắp xếp ngẫu nhiên
$sql = "SELECT * FROM questions WHERE difficulty = ? ORDER BY RAND() LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $difficulty);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $question = $result->fetch_assoc();
    echo json_encode($question);
} else {
    echo json_encode(['error' => 'Không tìm thấy câu hỏi']);
}

$stmt->close();
$conn->close();
?>
