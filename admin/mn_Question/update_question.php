<?php
include '../../user/conn_db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id =  $_POST['id'];
    $question =  $_POST['question'];
    $option_a =  $_POST['option_a'];
    $option_b =  $_POST['option_b'];
    $option_c =  $_POST['option_c'];
    $option_d =  $_POST['option_d'];
    $correct_option = strtoupper($_POST['correct_option']);
    $difficulty = $_POST['difficulty'];

    $sql = "UPDATE questions SET question='$question', 
            option_a='$option_a', option_b='$option_b', 
            option_c='$option_c', option_d='$option_d', 
            correct_option='$correct_option', difficulty='$difficulty'
            WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Cập nhật thông tin thành công');</script>";
        echo "<script>window.location.href='../manage_questions.php';</script>";
    } else {
        echo "<script>alert('Lỗi cập nhật: " . $conn->error . "');</script>";
        echo "<script>window.location.href='../manage_questions.php';</script>";
    }
}
$conn->close();
?>
