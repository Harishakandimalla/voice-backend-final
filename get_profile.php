<?php
header('Content-Type: application/json');
require_once 'db.php';
require_once 'helpers.php';

// optional CORS for dev
// header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Headers: Authorization, Content-Type");

$auth = jwtVerifyFromHeader();
if (!$auth) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}
$userId = $auth->id;

$pdo = getPDO();
$stmt = $pdo->prepare("SELECT id, email, name, display_name, occupation, verified, created_at FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!$user) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'User not found']);
    exit;
}
echo json_encode(['success' => true, 'user' => $user]);
