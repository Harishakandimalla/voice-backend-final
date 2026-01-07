<?php
header('Content-Type: application/json');

/* ---------- REQUIRED FILE ---------- */
require __DIR__ . '/db.php';

/* ---------- READ INPUT ---------- */
$data = json_decode(file_get_contents("php://input"), true);

$name     = trim($data['name'] ?? '');
$email    = trim($data['email'] ?? '');
$password = trim($data['password'] ?? '');

/* ---------- VALIDATIONS (INDIVIDUAL) ---------- */

// Name validation
if ($name === '') {
    http_response_code(400);
    echo json_encode([
        "ok" => false,
        "error" => "name_required"
    ]);
    exit;
}

// Email validation
if ($email === '') {
    http_response_code(400);
    echo json_encode([
        "ok" => false,
        "error" => "email_required"
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
        "ok" => false,
        "error" => "invalid_email"
    ]);
    exit;
}

// Password validation
if ($password === '') {
    http_response_code(400);
    echo json_encode([
        "ok" => false,
        "error" => "password_required"
    ]);
    exit;
}

if (strlen($password) < 6) {
    http_response_code(400);
    echo json_encode([
        "ok" => false,
        "error" => "password_too_short"
    ]);
    exit;
}

/* ---------- CHECK IF USER EXISTS ---------- */
$stmt = $pdo->prepare("SELECT id FROM Register WHERE email = ?");
$stmt->execute([$email]);

if ($stmt->fetch()) {
    echo json_encode([
        "ok" => false,
        "error" => "email_already_registered"
    ]);
    exit;
}

/* ---------- HASH PASSWORD ---------- */
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

/* ---------- INSERT USER ---------- */
$insert = $pdo->prepare("
    INSERT INTO Register (name, email, password)
    VALUES (?, ?, ?)
");

$insert->execute([
    $name,
    $email,
    $passwordHash
]);

/* ---------- SUCCESS RESPONSE ---------- */
echo json_encode([
    "ok" => true,
    "message" => "Registration successful"
]);
