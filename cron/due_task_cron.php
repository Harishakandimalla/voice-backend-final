<?php
require_once '../db.php';

$now = date('Y-m-d H:i');

$stmt = $pdo->query("
    SELECT id, user_id, title
    FROM tasks
    WHERE status='PENDING'
    AND TIMESTAMP(task_date, task_time) <= DATE_ADD('$now', INTERVAL 15 MINUTE)
");

foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $task) {
    $pdo->prepare("
        INSERT INTO notifications (user_id, title, message, type)
        VALUES (?, 'Task Due Soon', ?, 'TASK_DUE')
    ")->execute([
        $task['user_id'],
        "Task \"{$task['title']}\" starts soon"
    ]);
}
