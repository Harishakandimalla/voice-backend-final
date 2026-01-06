<?php
header("Content-Type: application/json");
require_once "../db.php";

$data = json_decode(file_get_contents("php://input"), true);

$user_id     = $data['user_id'] ?? null;
$task_id     = $data['task_id'] ?? null;
$title       = trim($data['title'] ?? '');
$description = trim($data['description'] ?? '');
$task_date   = $data['task_date'] ?? null;
$task_time   = $data['task_time'] ?? null;
$category    = $data['category'] ?? 'General';
$priority    = $data['priority'] ?? 'MEDIUM';

if (!$user_id || !$task_id || !$title || !$task_date) {
    echo json_encode([
        "ok" => false,
        "error" => "missing_fields"
    ]);
    exit;
}

/**
 * IMPORTANT:
 * ❌ Do NOT change status here
 * ❌ Do NOT auto-complete
 */
$stmt = $pdo->prepare("
    UPDATE tasks SET
        title = ?,
        description = ?,
        task_date = ?,
        task_time = ?,
        category = ?,
        priority = ?
    WHERE id = ?
    AND user_id = ?
");

$success = $stmt->execute([
    $title,
    $description,
    $task_date,
    $task_time,
    $category,
    $priority,
    $task_id,
    $user_id
]);

if ($success && $stmt->rowCount() > 0) {
    echo json_encode([
        "ok" => true,
        "message" => "Task updated successfully"
    ]);
} else {
    echo json_encode([
        "ok" => false,
        "error" => "task_not_found_or_no_change"
    ]);
}
