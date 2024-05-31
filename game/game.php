<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "millionaire";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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

// Lấy số lượng câu hỏi đã được trả lời từ yêu cầu GET
$answeredQuestions = isset($_GET['answered_questions']) ? intval($_GET['answered_questions']) : 0;

// Xác định mức độ câu hỏi dựa trên số lượng câu hỏi đã được trả lời
$difficulty = determineDifficulty($answeredQuestions);

// Truy vấn câu hỏi dựa trên mức độ và sắp xếp ngẫu nhiên
$sql = "SELECT * FROM questions WHERE difficulty = '$difficulty' ORDER BY RAND() LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $question = $result->fetch_assoc();
    echo json_encode($question);
} else {
    echo json_encode(['error' => 'No questions found']);
}

$conn->close();
?>