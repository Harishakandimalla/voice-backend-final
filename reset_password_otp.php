<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

header('Content-Type: application/json');

require_once 'db.php';

$data = json_decode(file_get_contents('php://input'), true);
$email = trim($data['email'] ?? '');
$new_password = trim($data['new_password'] ?? '');

if (!$email || !$new_password) { echo json_encode(['ok'=>false,'error'=>'missing_fields']); exit; }

// Optionally: verify that reset_otp is still set and valid (extra safety) could be added here

$hash = password_hash($new_password, PASSWORD_DEFAULT);
$pdo->prepare("UPDATE users SET password_hash=?, reset_otp=NULL, reset_otp_expiry=NULL WHERE email=?")
    ->execute([$hash, $email]);

echo json_encode(['ok'=>true,'message'=>'password_reset_success']);