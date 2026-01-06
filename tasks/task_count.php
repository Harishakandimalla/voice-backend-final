<?php
header("Content-Type: application/json");
require_once "../db.php";

$user_id = $_GET['user_id'] ?? null;

if (!$user_id) {
    echo json_encode([
        "ok" => false,
        "error" => "missing_user_id"
    ]);
    exit;
}

$today = date('Y-m-d');

/**
 * COUNT LOGIC:
 * TODAY     → task_date = today AND status != COMPLETED
 * UPCOMING  → task_date > today AND status != COMPLETED
 * PENDING   → task_date < today AND status != COMPLETED
 * COMPLETED → status = COMPLETED
 */

$todayCount = $pdo->prepare("
    SELECT COUNT(*) FROM tasks
    WHERE user_id = ?
    AND task_date = ?
    AND status != 'COMPLETED'
");
$todayCount->execute([$user_id, $today]);

$upcomingCount = $pdo->prepare("
    SELECT COUNT(*) FROM tasks
    WHERE user_id = ?
    AND task_date > ?
    AND status != 'COMPLETED'
");
$upcomingCount->execute([$user_id, $today]);

$pendingCount = $pdo->prepare("
    SELECT COUNT(*) FROM tasks
    WHERE user_id = ?
    AND task_date < ?
    AND status != 'COMPLETED'
");
$pendingCount->execute([$user_id, $today]);

$completedCount = $pdo->prepare("
    SELECT COUNT(*) FROM tasks
    WHERE user_id = ?
    AND status = 'COMPLETED'
");
$completedCount->execute([$user_id]);

echo json_encode([
    "ok" => true,
    "today"     => (int)$todayCount->fetchColumn(),
    "upcoming"  => (int)$upcomingCount->fetchColumn(),
    "pending"   => (int)$pendingCount->fetchColumn(),
    "completed" => (int)$completedCount->fetchColumn()
]);
