<?php
header('Content-Type: application/json');
require_once 'groq_client.php';

$input = json_decode(file_get_contents("php://input"), true);
$text = trim($input['text'] ?? '');

if ($text === '') {
    echo json_encode(["ok" => false, "error" => "empty_message"]);
    exit;
}

$response = groqRequest([
    ["role" => "system", "content" => "You are a helpful AI assistant for a task management app."],
    ["role" => "user", "content" => $text]
]);

if (!$response) {
    echo json_encode(["ok" => false, "error" => "ai_failed"]);
    exit;
}

echo json_encode([
    "ok" => true,
    "reply" => $response
]);
