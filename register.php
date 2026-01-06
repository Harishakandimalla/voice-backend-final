<?php
header('Content-Type: application/json');

/* ---------- REQUIRED FILES ---------- */
require __DIR__ . '/db.php';
require __DIR__ . '/mailer.php';

/* ---------- READ INPUT ---------- */
$data = json_decode(file_get_contents("php://input"), true);

$name     = trim($data['name'] ?? '');
$email    = trim($data['email'] ?? '');
$password = trim($data['password'] ?? '');

/* ---------- VALIDATION ---------- */
if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
    echo json_encode([
        'ok' => false,
        'error' => 'invalid_input'
    ]);
    exit;
}

/* ---------- CHECK IF USER EXISTS ---------- */
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);

if ($stmt->rowCount() > 0) {
    echo json_encode([
        'ok' => false,
        'error' => 'email_already_registered'
    ]);
    exit;
}

/* ---------- HASH PASSWORD ---------- */
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

/* ---------- GENERATE OTP ---------- */
$otp = random_int(100000, 999999);
$otpExpiry = date('Y-m-d H:i:s', strtotime('+15 minutes'));

/* ---------- INSERT USER ---------- */
$insert = $pdo->prepare("
    INSERT INTO users 
    (name, email, password_hash, verification_code, verification_expiry, verified)
    VALUES (?, ?, ?, ?, ?, 0)
");

$insert->execute([
    $name,
    $email,
    $passwordHash,
    $otp,
    $otpExpiry
]);

/* ---------- SEND VERIFICATION EMAIL ---------- */
try {
    $mail = getMailer();
    $mail->addAddress($email);

    $mail->Subject = 'Verify Your Account';
    $mail->Body = "
        <h2>Welcome to Voice Command App</h2>
        <p>Your verification OTP is:</p>
        <h1>$otp</h1>
        <p>This code is valid for 15 minutes.</p>
    ";

    $mail->send();

    echo json_encode([
        'ok' => true,
        'message' => 'Registration successful. Verification OTP sent.'
    ]);

} catch (Exception $e) {
    echo json_encode([
        'ok' => false,
        'error' => 'email_sending_failed'
    ]);
}
