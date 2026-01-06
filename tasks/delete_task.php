<?php
header("Content-Type: application/json");
require_once "../db.php";
require_once "../utils/response.php";

$data = json_decode(file_get_contents("php://input"), true);
$task_id = $data["task_id"] ?? null;

if (!$task_id) {
    error("missing_task_id");
}

$stmt = $pdo->prepare("DELETE FROM tasks WHERE id=?");
$stmt->execute([$task_id]);

success();
