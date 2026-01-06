<?php
// profile.php
session_start();
header('Content-Type: application/json; charset=utf-8');
if (empty($_SESSION['user_id'])) { http_response_code(401); echo json_encode(['ok'=>false,'error'=>'unauthorized']); exit; }

// you can also fetch fresh user data from DB if needed
require_once 'db.php';
$stmt = $pdo->prepare("SELECT id,name,email,display_name,occupation,created_at FROM users WHERE id = ? LIMIT 1");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
echo json_encode(['ok'=>true,'user'=>$user]);
