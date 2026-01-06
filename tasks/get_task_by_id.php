<?php
header("Content-Type: application/json");
require_once "../db.php";
require_once "../utils/response.php";

$task_id = $_GET["task_id"] ?? null;

if (!$task_id) {
    error("missing_task_id");
}

$stmt = $pdo->prepare("SELECT * FROM tasks WHERE id=?");
$stmt->execute([$task_id]);

$task = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$task) {
    error("task_not_found");
}

success(["task" => $task]);
