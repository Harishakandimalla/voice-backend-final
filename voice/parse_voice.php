<?php
header('Content-Type: application/json');
require_once '../db.php';

$data = json_decode(file_get_contents('php://input'), true);
$text = strtolower(trim($data['text'] ?? ''));

if ($text === '') {
    echo json_encode(['ok'=>false,'error'=>'no_voice_text']);
    exit;
}

$title = $text;
$date = date('Y-m-d');
$time = '09:00';
$category = 'General';
$priority = 'MEDIUM';

// time detection
if (preg_match('/(\d{1,2})(?::(\d{2}))?\s*(am|pm)/', $text, $m)) {
    $hour = (int)$m[1];
    $minute = isset($m[2]) ? (int)$m[2] : 0;
    if ($m[3] === 'pm' && $hour < 12) $hour += 12;
    if ($m[3] === 'am' && $hour == 12) $hour = 0;
    $time = sprintf('%02d:%02d', $hour, $minute);
}

// keyword detection
if (str_contains($text, 'meeting')) $category = 'Work';
if (str_contains($text, 'urgent')) $priority = 'HIGH';
if (str_contains($text, 'today')) $date = date('Y-m-d');
if (str_contains($text, 'tomorrow')) $date = date('Y-m-d', strtotime('+1 day'));

$stmt = $pdo->prepare("
    INSERT INTO tasks (title, task_date, task_time, category, priority, status)
    VALUES (?,?,?,?,?,?)
");

$stmt->execute([
    ucfirst($title),
    $date,
    $time,
    $category,
    $priority,
    'UPCOMING'
]);

echo json_encode([
    'ok' => true,
    'task_id' => $pdo->lastInsertId()
]);
