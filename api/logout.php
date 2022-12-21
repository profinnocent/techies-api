<?php
session_start();

session_destroy();

http_response_code(200);
echo json_encode(['status'=>1, 'message'=>'You are logged out successfully.']);

// header('Location: ../view/index.php');