<?php
header('Content-Type: application/json');
require_once 'groq_client.php';

$input = json_decode(file_get_contents("php://input"), true);
$text = trim($input['text'] ?? '');

if ($text === '') {
    echo json_encode(["ok"=>false,"error"=>"no_voice_text"]);
    exit;
}

$prompt = "
Convert this voice command into JSON.
Return ONLY JSON. No explanation.

Fields:
title
date (YYYY-MM-DD)
time (HH:MM or null)
category
priority (LOW, MEDIUM, HIGH)

Sentence:
$text
";

$result = groqRequest([
    ["role"=>"system","content"=>"You are a task extraction AI."],
    ["role"=>"user","content"=>$prompt]
]);

// 🚨 If Groq failed
if (is_array($result) && isset($result['__error'])) {
    echo json_encode([
        "ok" => false,
        "error" => $result['__error'],
        "details" => $result
    ]);
    exit;
}

if (!$result) {
    echo json_encode([
        "ok"=>false,
        "error"=>"empty_ai_response"
    ]);
    exit;
}

$data = json_decode($result, true);

if (!$data) {
    echo json_encode([
        "ok"=>false,
        "error"=>"invalid_ai_response",
        "raw"=>$result
    ]);
    exit;
}

echo json_encode([
    "ok"=>true,
    "task"=>$data
]);
