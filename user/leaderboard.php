<?php
include 'conn_db.php';

$sql = "SELECT username, high_score FROM users 
        JOIN game_session ON users.id = game_session.user_id 
        ORDER BY game_session.high_score DESC 
        LIMIT 8";
$result = $conn->query($sql);

$leaderboard = [];
if($result->num_rows >0){
    while($row = $result->fetch_assoc()){
        $leaderboard[] =  $row;
    }
}

header('Content-Type: application/json');
echo json_encode($leaderboard);

$conn->close();
?>