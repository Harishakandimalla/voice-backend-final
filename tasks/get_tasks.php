<?php
header('Content-Type: application/json');
require_once '../db.php';

$user_id = intval($_GET['user_id'] ?? 0);
$filter  = $_GET['filter'] ?? 'all';

if ($user_id <= 0) {
    echo json_encode(['ok'=>false,'error'=>'missing_user_id']);
    exit;
}

$today = date('Y-m-d');

$sql = "SELECT 
            id,
            title,
            description,
            task_date,
            task_time,
            category,
            priority,
            status
        FROM tasks
        WHERE user_id = :user_id";

switch ($filter) {
    case 'today':
        $sql .= " AND task_date = :today AND status != 'COMPLETED'";
        break;

    case 'pending':
        $sql .= " AND task_date < :today AND status != 'COMPLETED'";
        break;

    case 'upcoming':
        $sql .= " AND task_date > :today AND status != 'COMPLETED'";
        break;

    case 'completed':
        $sql .= " AND status = 'COMPLETED'";
        break;
}

$sql .= " ORDER BY task_date ASC, task_time ASC";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

if (in_array($filter, ['today','pending','upcoming'])) {
    $stmt->bindParam(':today', $today);
}

$stmt->execute();

echo json_encode([
    'ok' => true,
    'tasks' => $stmt->fetchAll(PDO::FETCH_ASSOC)
]);
