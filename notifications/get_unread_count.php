<?php
header('Content-Type: application/json');
require_once '../db.php';

$count = $pdo->query("
    SELECT COUNT(*) FROM notifications
    WHERE is_read=0
")->fetchColumn();

echo json_encode([
    'ok' => true,
    'unread' => (int)$count
]);
