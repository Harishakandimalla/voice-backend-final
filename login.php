<?php
header('Content-Type: application/json');

require __DIR__ . '/db.php';

/* ---------- INPUT ---------- */
$data = json_decode(file_get_contents("php://input"), true);

$email    = trim($data['email'] ?? '');
$password = trim($data['password'] ?? '');

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
    http_response_code(400);
    echo json_encode([
        'ok' => false,
        'error' => 'invalid_input'
    ]);
    exit;
}

/* ---------- FETCH USER ---------- */
$stmt = $pdo->prepare("
    SELECT id, password_hash, verified 
    FROM users 
    WHERE email = ?
");
$stmt->execute([$email]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    http_response_code(401);
    echo json_encode([
        'ok' => false,
        'error' => 'invalid_credentials'
    ]);
    exit;
}

/* ---------- CHECK VERIFIED ---------- */
if ((int)$user['verified'] !== 1) {
    http_response_code(403);
    echo json_encode([
        'ok' => false,
        'error' => 'email_not_verified'
    ]);
    exit;
}

/* ---------- CHECK PASSWORD ---------- */
if (!password_verify($password, $user['password_hash'])) {
    http_response_code(401);
    echo json_encode([
        'ok' => false,
        'error' => 'invalid_credentials'
    ]);
    exit;
}

/* ---------- LOGIN SUCCESS ---------- */
echo json_encode([
    'ok' => true,
    'message' => 'Login successful',
    'user_id' => $user['id']
]);
