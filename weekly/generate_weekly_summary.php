<?php
header('Content-Type: application/json');
require_once '../db.php';

$userId = 1;

// current week (Monday → Sunday)
$weekStart = date('Y-m-d', strtotime('monday this week'));
$weekEnd   = date('Y-m-d', strtotime('sunday this week'));

// count tasks
$stmt = $pdo->prepare("
    SELECT 
      COUNT(*) AS total,
      SUM(status='COMPLETED') AS completed,
      SUM(status!='COMPLETED') AS pending
    FROM tasks
    WHERE task_date BETWEEN ? AND ?
");
$stmt->execute([$weekStart, $weekEnd]);
$row = $stmt->fetch();

// save snapshot
$stmt = $pdo->prepare("
    INSERT INTO weekly_summary
    (user_id, week_start, week_end, total_tasks, completed_tasks, pending_tasks)
    VALUES (?,?,?,?,?,?)
");
$stmt->execute([
    $userId,
    $weekStart,
    $weekEnd,
    $row['total'],
    $row['completed'],
    $row['pending']
]);

echo json_encode([
    'ok' => true,
    'week_start' => $weekStart,
    'week_end' => $weekEnd,
    'total_tasks' => (int)$row['total'],
    'completed_tasks' => (int)$row['completed'],
    'pending_tasks' => (int)$row['pending']
]);
