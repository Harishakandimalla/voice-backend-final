<?php
// db.php
// Central database connection file

$DB_HOST = 'localhost';
$DB_NAME = 'voiceapp';
$DB_USER = 'root';
$DB_PASS = '';   // default XAMPP password

try {
    $pdo = new PDO(
        "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    // Never expose DB error details to client
    header('Content-Type: application/json');
    echo json_encode([
        'ok' => false,
        'error' => 'Database connection failed'
    ]);
    exit;
}
