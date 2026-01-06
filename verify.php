<?php
// verify.php
header('Content-Type: application/json; charset=utf-8');
require_once 'db.php';

$raw = file_get_contents('php://input');
$data = json_decode($raw,true) ?: $_POST;
$email = trim($data['email'] ?? '');
$code = trim($data['code'] ?? '');

if (!$email || !$code) { http_response_code(400); echo json_encode(['ok'=>false,'error'=>'missing']); exit; }

$stmt = $pdo->prepare("SELECT id, verification_expiry FROM users WHERE email = ? AND verification_code = ? AND verified = 0 LIMIT 1");
$stmt->execute([$email, $code]);
$row = $stmt->fetch();
if (!$row) { http_response_code(400); echo json_encode(['ok'=>false,'error'=>'invalid_code_or_already_verified']); exit; }

if (strtotime($row['verification_expiry']) < time()) {
    http_response_code(400);
    echo json_encode(['ok'=>false,'error'=>'otp_expired']);
    exit;
}

// mark verified
$up = $pdo->prepare("UPDATE users SET verified = 1, verification_code = NULL, verification_expiry = NULL WHERE id = ?");
$up->execute([$row['id']]);
echo json_encode(['ok'=>true,'message'=>'verified']);
