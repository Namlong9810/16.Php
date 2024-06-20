<?php
include '../../user/conn_db.php';

if(isset($_GET['id'])){
    $id = $_GET['id'];

    $sql = "DELETE FROM questions where id = '$id'";

    if($conn->query($sql)===true){
        echo "<script>alert('Đã xóa câu hỏi thành công!!');</script>";
    }else{
        echo "Error: ". $sql." ". $conn->error;
    }

    $conn->close();
    header("Location: ../manage_questions.php");
    exit();
}
?>