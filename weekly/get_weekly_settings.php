<?php
header('Content-Type: application/json');
require_once '../db.php';

$userId = 1;

$stmt = $pdo->prepare("
    SELECT weekly_day, weekly_time
    FROM user_settings
    WHERE user_id = ?
");
$stmt->execute([$userId]);

$row = $stmt->fetch();

if (!$row) {
    echo json_encode(['ok'=>false,'error'=>'settings_not_found']);
    exit;
}

echo json_encode([
    'ok' => true,
    'weekly_day' => $row['weekly_day'],
    'weekly_time' => $row['weekly_time']
]);
