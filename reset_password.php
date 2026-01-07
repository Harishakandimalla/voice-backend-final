<?php
header('Content-Type: application/json');

require __DIR__ . '/db.php';

/* ---------- INPUT ---------- */
$data = json_decode(file_get_contents("php://input"), true);

$email = trim($data['email'] ?? '');
$new_password = trim($data['new_password'] ?? '');

/* ---------- VALIDATION ---------- */
if ($email === '' || $new_password === '') {
    echo json_encode(["ok"=>false,"error"=>"missing_fields"]);
    exit;
}

if (strlen($new_password) < 6) {
    echo json_encode(["ok"=>false,"error"=>"password_too_short"]);
    exit;
}

/* ---------- HASH PASSWORD ---------- */
$hash = password_hash($new_password, PASSWORD_DEFAULT);

/* ---------- UPDATE PASSWORD ---------- */
$stmt = $pdo->prepare("
    UPDATE `register`
    SET password=?, reset_otp=NULL, reset_otp_expiry=NULL
    WHERE email=?
");
$stmt->execute([$hash, $email]);

echo json_encode([
    "ok"=>true,
    "message"=>"password_reset_success"
]);
