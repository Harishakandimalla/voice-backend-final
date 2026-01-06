<?php
require_once '../db.php';

$users = $pdo->query("SELECT DISTINCT user_id FROM tasks")->fetchAll();

foreach ($users as $u) {
    $count = $pdo->query("
        SELECT COUNT(*) FROM tasks
        WHERE user_id={$u['user_id']}
        AND status='COMPLETED'
        AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
    ")->fetchColumn();

    $pdo->prepare("
        INSERT INTO notifications (user_id, title, message, type)
        VALUES (?, 'Weekly Summary Ready', ?, 'WEEKLY_SUMMARY')
    ")->execute([
        $u['user_id'],
        "You completed $count tasks this week 🎉"
    ]);
}
