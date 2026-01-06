<?php
header('Content-Type: application/json');
require_once '../db.php';

$data = json_decode(file_get_contents('php://input'), true);

$stmt = $pdo->prepare("
INSERT INTO notifications (title, message, type)
VALUES (?,?,?)
");

$stmt->execute([
    $data['title'],
    $data['message'],
    $data['type'] // activity | reminder | ai
]);

echo json_encode(['ok'=>true]);
