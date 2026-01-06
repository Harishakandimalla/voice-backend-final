<?php
header('Content-Type: application/json');
require_once '../db.php';

$userId = 1; // static for now

$stmt = $pdo->prepare("
    SELECT notification_sound, weekly_day, weekly_time
    FROM user_settings
    WHERE user_id = ?
");
$stmt->execute([$userId]);

$settings = $stmt->fetch();

if (!$settings) {
    echo json_encode(['ok'=>false,'error'=>'settings_not_found']);
    exit;
}

echo json_encode([
    'ok' => true,
    'settings' => $settings
]);
