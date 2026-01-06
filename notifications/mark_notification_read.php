<?php
header('Content-Type: application/json');
require_once '../db.php';

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'] ?? null;

if (!$id) {
    echo json_encode(["ok" => false]);
    exit;
}

$pdo->prepare("UPDATE notifications SET is_read=1 WHERE id=?")
    ->execute([$id]);

echo json_encode(["ok" => true]);
