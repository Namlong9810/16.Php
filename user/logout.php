<?php
session_start();
session_unset();
session_destroy();
echo json_encode(['success' => true]);
header("Location: ../login.html"); // Thay đổi URL theo đường dẫn của bạn
exit();
?>
