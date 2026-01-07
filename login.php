<?php
header('Content-Type: application/json');

require __DIR__ . '/db.php';

/* ---------- INPUT ---------- */
$data = json_decode(file_get_contents("php://input"), true);

$email    = trim($data['email'] ?? '');
$password = trim($data['password'] ?? '');

/* ---------- VALIDATION ---------- */
if ($email === '') {
    echo json_encode(["ok"=>false,"error"=>"email_required"]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["ok"=>false,"error"=>"invalid_email"]);
    exit;
}

if ($password === '') {
    echo json_encode(["ok"=>false,"error"=>"password_required"]);
    exit;
}

/* ---------- FETCH USER ---------- */
$stmt = $pdo->prepare("
    SELECT id, name, email, password
    FROM `register`
    WHERE email = ?
");
$stmt->execute([$email]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

/* ---------- CHECK USER ---------- */
if (!$user) {
    echo json_encode([
        "ok"=>false,
        "error"=>"email invalid_credentials"
    ]);
    exit;
}

/* ---------- CHECK PASSWORD ---------- */
if (!password_verify($password, $user['password'])) {
    echo json_encode([
        "ok"=>false,
        "error"=>"password invalid_credentials"
    ]);
    exit;
}

/* ---------- LOGIN SUCCESS ---------- */
echo json_encode([
    "ok"=>true,
    "message"=>"login_success",
    "user"=>[
        "id"=>$user['id'],
        "name"=>$user['name'],
        "email"=>$user['email']
    ]
]);
