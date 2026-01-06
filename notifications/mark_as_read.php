<?php
header('Content-Type: application/json');
require_once '../db.php';

$data = json_decode(file_get_contents('php://input'), true);
$id = intval($data['id'] ?? 0);

if ($id <= 0) {
    echo json_encode(['ok'=>false,'error'=>'invalid_id']);
    exit;
}

$stmt = $pdo->prepare("
    UPDATE notifications
    SET is_read=1
    WHERE id=?
");

$stmt->execute([$id]);

echo json_encode(['ok'=>true]);
