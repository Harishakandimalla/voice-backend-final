<?php
$config = require __DIR__ . '/groq_config.php';

function groqRequest(array $messages)
{
    global $config;

    $url = "https://api.groq.com/openai/v1/chat/completions";

    $payload = [
        "model" => $config['model'],
        "messages" => $messages,
        "temperature" => 0.2
    ];

    $ch = curl_init($url);

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer " . $config['api_key'],
            "Content-Type: application/json"
        ],
        CURLOPT_POSTFIELDS => json_encode($payload)
    ]);

    $response = curl_exec($ch);

    if ($response === false) {
        $error = curl_error($ch);
        curl_close($ch);
        return ["__error" => "curl_error", "message" => $error];
    }

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $data = json_decode($response, true);

    if ($httpCode !== 200) {
        return [
            "__error" => "http_error",
            "status" => $httpCode,
            "response" => $data
        ];
    }

    return $data['choices'][0]['message']['content'] ?? null;
}
