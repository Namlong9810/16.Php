<?php
include '../user/conn_db.php';
session_start();

//Bắt đầu bộ đệm đầu ra 
ob_start();
header('Content-type: application/json');


$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($_SESSION['user_id']) && isset($_SESSION['session_id'])) {
        $user_id = $_SESSION['user_id'];
        $session_id = $_SESSION['session_id'];
        $score = $data['score'];
        $answered_Questions = $data['answered_Questions'];
        $use_help_50_50 = $data['use_help_50_50'];
        $use_help_wise_man = $data['use_help_wise_man'];
        $use_help_company = $data['use_help_companion'];

        //Thực hiện truy vấn lấy điểm có sẵn trong csdl
        $select = $conn->prepare("SELECT high_score, all_answered_Questions FROM game_session WHERE id = ? AND user_id = ?");
        $select->bind_param("ii", $session_id, $user_id);
        $select->execute();
        $select->bind_result($current_score, $all_answered_Questions); // Sử dụng nhiều biến cho nhiều cột
        $select->fetch();
        $select->close();

        //Tạo biến lưu trữ các câu hỏi trả lời được
        $new_answered_Questions = $answered_Questions +$all_answered_Questions;

        if($score > $current_score){
            $stmt_update_score = $conn->prepare("UPDATE game_session SET high_score = ?, all_answered_Questions = ?, use_help_50_50 = ?, use_help_wise_man = ?, use_help_companion = ? WHERE id = ? AND user_id = ?");
            $stmt_update_score->bind_param("iiiiiii",$score,$new_answered_Questions,$use_help_50_50,$use_help_wise_man,$use_help_company,$session_id,$user_id);
            if($stmt_update_score->execute()){
                $response['success'] = true;
            } else{
                $response['error'] = 'Failed to save game session';
            }
            $stmt_update_score->close();
        }else{$stmt = $conn->prepare("UPDATE game_session SET all_answered_Questions = ?, use_help_50_50 = ?, use_help_wise_man = ?, use_help_companion = ? WHERE id = ? AND user_id = ?");
            $stmt->bind_param("iiiiii",$new_answered_Questions,$use_help_50_50,$use_help_wise_man,$use_help_company,$session_id,$user_id);
            if ($stmt->execute()) {
                $response['success'] = true;
            }
            else {
                $response['error'] = 'Failed to save game session';
            }
            $stmt->close();
        }
    } else {
        $response['error'] = 'User not logged in or session not found';
    }
} else {
    $response['error'] = 'Invalid request method';
}
ob_end_clean();
echo json_encode($response);
?>
