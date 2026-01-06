<?php
header('Content-Type: application/json');
require_once '../db.php';

$data = json_decode(file_get_contents('php://input'), true);
$userId = 1;

// Read fields safely (allow partial updates)
$sound = $data['notification_sound'] ?? null;
$day   = $data['weekly_day'] ?? null;
$time  = $data['weekly_time'] ?? null;

$fields = [];
$values = [];

if ($sound !== null) {
    $fields[] = "notification_sound = ?";
    $values[] = $sound;
}
if ($day !== null) {
    $fields[] = "weekly_day = ?";
    $values[] = $day;
}
if ($time !== null) {
    $fields[] = "weekly_time = ?";
    $values[] = $time;
}

if (empty($fields)) {
    echo json_encode(['ok'=>false,'error'=>'no_data']);
    exit;
}

$values[] = $userId;

$sql = "UPDATE user_settings SET ".implode(', ', $fields)." WHERE user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute($values);

echo json_encode(['ok'=>true]);
