<?php
require_once '../db.php';

$now = date('Y-m-d H:i');

$sql = "
SELECT id, user_id, title
FROM tasks
WHERE reminder_time = ?
AND status = 'PENDING'
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$now]);

foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $task) {
    $pdo->prepare("
        INSERT INTO notifications (user_id, title, message, type)
        VALUES (?, 'Reminder', ?, 'REMINDER')
    ")->execute([
        $task['user_id'],
        "Reminder for task \"{$task['title']}\""
    ]);
}
