<?php
include '../../user/conn_db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $question = $_POST['question'];
    $option_a = $_POST['option_a'];
    $option_b = $_POST['option_b'];
    $option_c = $_POST['option_c'];
    $option_d = $_POST['option_d'];
    $correct_option = $_POST['correct_option'];
    $difficulty = $_POST['difficulty'];

    $error_message = "";


    if ($difficulty > 3) {
        $error_message .= "Độ khó phải nhỏ hơn hoặc bằng 3. ";
    }

    if (!($correct_option === $option_a || $correct_option === $option_b || 
          $correct_option === $option_c || $correct_option === $option_d)) {
        $error_message .= "Đáp án đúng phải là một trong bốn đáp án đã nhập. ";
    }

    $options = array($option_a, $option_b, $option_c, $option_d);
    if (count($options) !== count(array_unique($options))) {
        $error_message .= "Các đáp án không được trùng nhau. ";
    }

    if ($error_message === "") {
        $stmt = $conn->prepare("INSERT INTO questions (question, option_a, option_b, option_c, option_d, correct_option, difficulty) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssi", $question, $option_a, $option_b, $option_c, $option_d, $correct_option, $difficulty);

        if ($stmt->execute()) {
            echo "<script>alert('Câu hỏi đã được thêm thành công.'); window.location.href='../manage_questions.php';</script>";
        } else {
            echo "<script>alert('Lỗi: " . $stmt->error . "');</script>";
        }

        $stmt->close();
    } else {
        // Display error message
        echo "<script>alert('$error_message'); window.location.href='../manage_questions.php';</script></script>";
    }
} else {
    echo "<script>alert('Lỗi kết nối phương thức');</script>";
}

$conn->close();
?>
