<?php
require_once '../db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

$user_id   = intval($data['user_id'] ?? 0);
$title     = trim($data['title'] ?? '');
$desc      = trim($data['description'] ?? '');

// ✅ DEFAULTS
$date      = $data['task_date'] ?? date('Y-m-d');
$time      = $data['task_time'] ?? null;
$category  = $data['category'] ?? 'Work';
$priority  = $data['priority'] ?? 'MEDIUM';

if ($user_id === 0 || $title === '') {
    echo json_encode(["ok"=>false,"error"=>"missing_fields"]);
    exit;
}

$stmt = $pdo->prepare("
    INSERT INTO tasks
    (user_id, title, description, task_date, task_time, category, priority, status)
    VALUES (?, ?, ?, ?, ?, ?, ?, 'PENDING')
");

$stmt->execute([
    $user_id,
    $title,
    $desc,
    $date,
    $time,
    $category,
    strtoupper($priority)
]);

echo json_encode([
    "ok" => true,
    "task_id" => $pdo->lastInsertId()
]);
