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

$input = readInput();
$display_name = trim($input['display_name'] ?? '');
$occupation = trim($input['occupation'] ?? '');

$pdo = getPDO();
$stmt = $pdo->prepare("UPDATE users SET display_name = ?, occupation = ? WHERE id = ?");
$stmt->execute([$display_name ?: null, $occupation ?: null, $userId]);

echo json_encode(['success' => true, 'message' => 'Profile updated']);
