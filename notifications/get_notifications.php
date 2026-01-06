<?php
header('Content-Type: application/json');
require_once '../db.php';

$user_id = $_GET['user_id'] ?? null;
if (!$user_id) {
    echo json_encode(["ok" => false, "error" => "missing_user_id"]);
    exit;
}

$stmt = $pdo->prepare("
    SELECT id, title, message, type, created_at, is_read
    FROM notifications
    WHERE user_id = ?
    AND type != 'TASK_CREATED'
    ORDER BY created_at DESC
");

$stmt->execute([$user_id]);

$notifications = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $notifications[] = [
        "id" => (int)$row['id'],
        "title" => $row['title'],
        "message" => $row['message'],
        "type" => $row['type'],
        "createdAt" => $row['created_at'],
        "isRead" => (bool)$row['is_read']
    ];
}

echo json_encode([
    "ok" => true,
    "notifications" => $notifications
]);
