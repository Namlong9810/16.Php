<?php
include '../user/conn_db.php';
session_start();

header('Content-type: application/json');

$response = array();

if (isset($_SESSION['user_id']) && isset($_SESSION['session_id'])) {
    $user_id = $_SESSION['user_id'];
    $session_id = $_SESSION['session_id'];

    $stmt = $conn->prepare("SELECT current_score, current_question, use_help_50_50, use_help_wise_man, use_help_companion FROM game_session WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $session_id, $user_id);
    $stmt->execute();
    $stmt->bind_result($current_score, $current_question, $use_help_50_50, $use_help_wise_man, $use_help_companion);
    
    if ($stmt->fetch()) {
        $response = array(
            'current_score' => $current_score,
            'answered_questions' => $current_question,
            'use_help_50_50' => $use_help_50_50,
            'use_help_wise_man' => $use_help_wise_man,
            'use_help_companion' => $use_help_companion
        );
    } else {
        $response['error'] = 'No game session found';
    }
    
    $stmt->close();
} else {
    $response['error'] = 'User not logged in or session not found';
}

echo json_encode($response);
?>
