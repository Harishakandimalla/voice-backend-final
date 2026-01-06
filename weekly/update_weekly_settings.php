<?php
header('Content-Type: application/json');
require_once '../db.php';

$data = json_decode(file_get_contents('php://input'), true);
$userId = 1;

$day  = trim($data['weekly_day'] ?? '');
$time = trim($data['weekly_time'] ?? '');

if ($day === '' || $time === '') {
    echo json_encode(['ok'=>false,'error'=>'missing_fields']);
    exit;
}

$stmt = $pdo->prepare("
    UPDATE user_settings
    SET weekly_day=?, weekly_time=?
    WHERE user_id=?
");
$stmt->execute([$day, $time, $userId]);

echo json_encode(['ok'=>true]);
