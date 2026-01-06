<?php
header('Content-Type: application/json');
require_once 'groq_client.php';

$response = groqRequest([
    ["role"=>"system","content"=>"Generate 5 short productivity task suggestions."],
    ["role"=>"user","content"=>"Give me task ideas"]
]);

echo json_encode([
    "ok"=>true,
    "suggestions"=>explode("\n", $response)
]);
