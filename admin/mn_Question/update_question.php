<?php
include '../../user/conn_db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id =  $_POST['id'];
    $question =  $_POST['question'];
    $option_a =  $_POST['option_a'];
    $option_b =  $_POST['option_b'];
    $option_c =  $_POST['option_c'];
    $option_d =  $_POST['option_d'];
    $correct_option = $_POST['correct_option'];
    $difficulty = $_POST['difficulty'];

    if($correct_option !== $option_a && $correct_option !== $option_b && $correct_option !== $option_c && $correct_option !== $option_d){
        echo "<script>alert('Đáp án đúng phải trùng khớp với một trong 4 đáp án A, B, C, hoặc D.');</script>";
        echo "<script>window.location.href='../manage_questions.php';</script>";
    }else if ($option_a === $option_b || $option_a === $option_c || $option_a === $option_d || $option_b === $option_c || $option_b === $option_d || $option_c === $option_d) {
        echo "<script>alert('Các đáp án A, B, C, và D không được trùng nhau.');</script>";
        echo "<script>window.location.href='../manage_questions.php';</script>";
    }else if($difficulty<1||$difficulty>3){
        echo "<script>alert('Độ khó phải thuộc 3 mức độ 1,2,3!');</script>";
        echo "<script>window.location.href='../manage_questions.php';</script>";
    }
    else{
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
}
$conn->close();
?>
