<?php
header('Content-Type: application/json');
require_once '../db.php';

$data = json_decode(file_get_contents('php://input'), true);
$task_id = $data['task_id'] ?? null;
$user_id = $data['user_id'] ?? null;

if (!$task_id || !$user_id) {
    echo json_encode(["ok" => false, "error" => "missing_fields"]);
    exit;
}

$pdo->prepare("
    UPDATE tasks SET status='COMPLETED'
    WHERE id=? AND user_id=?
")->execute([$task_id, $user_id]);

$title = $pdo->query("
    SELECT title FROM tasks WHERE id=$task_id
")->fetchColumn();

$pdo->prepare("
    INSERT INTO notifications (user_id, title, message, type)
    VALUES (?, 'Task Completed', ?, 'TASK_COMPLETED')
")->execute([
    $user_id,
    "Task \"$title\" marked as done"
]);

echo json_encode(["ok" => true]);
