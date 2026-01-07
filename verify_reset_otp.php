<?php
header('Content-Type: application/json');

require __DIR__ . '/db.php';

/* ---------- INPUT ---------- */
$data = json_decode(file_get_contents("php://input"), true);

$email = trim($data['email'] ?? '');
$otp   = trim($data['otp'] ?? '');

/* ---------- VALIDATION ---------- */
if ($email === '' || $otp === '') {
    echo json_encode(["ok"=>false,"error"=>"missing_fields"]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["ok"=>false,"error"=>"invalid_email"]);
    exit;
}

/* ---------- FETCH OTP ---------- */
$stmt = $pdo->prepare("
    SELECT reset_otp, reset_otp_expiry
    FROM `register`
    WHERE email=?
");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !$user['reset_otp']) {
    echo json_encode(["ok"=>false,"error"=>"otp_not_requested"]);
    exit;
}

/* ---------- CHECK EXPIRY ---------- */
if (strtotime($user['reset_otp_expiry']) < time()) {
    echo json_encode(["ok"=>false,"error"=>"otp_expired"]);
    exit;
}

/* ---------- CHECK OTP ---------- */
if ($user['reset_otp'] !== $otp) {
    echo json_encode(["ok"=>false,"error"=>"invalid_otp"]);
    exit;
}

/* ---------- OTP VERIFIED ---------- */
echo json_encode([
    "ok"=>true,
    "message"=>"otp_verified",
    "next"=>"reset_password"
]);
