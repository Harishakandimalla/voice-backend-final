<?php
// Always start by including the Composer autoloader
require 'vendor/autoload.php';

// Tell the app that this script will always return JSON
header('Content-Type: application/json');

// --- Database Connection ---
$host = 'localhost';
$dbname = 'voiceapp'; // Verify your database name
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // If the database connection fails, send a clean JSON error.
    echo json_encode(['ok' => false, 'error' => 'Database connection failed.']);
    exit();
}
// --- End Database Connection ---


// --- Start of Password Reset Logic ---
$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'] ?? '';
$newPassword = $data['password'] ?? '';

// 1. Basic validation
if (empty($email) || empty($newPassword)) {
    echo json_encode(['ok' => false, 'error' => 'Email and new password are required.']);
    exit();
}

if (strlen($newPassword) < 8) {
    echo json_encode(['ok' => false, 'error' => 'Password must be at least 8 characters.']);
    exit();
}

// 2. CRITICAL: Hash the new password before saving it.
// This is the most important security step.
$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

// 3. Update the user's password in the database
try {
    $stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE email = ?");
    $stmt->execute([$hashedPassword, $email]);

    // Check if any row was actually updated
    if ($stmt->rowCount() > 0) {
        // 4. On success, send a clean JSON response.
        echo json_encode(['ok' => true, 'message' => 'Password has been reset successfully.']);
    } else {
        echo json_encode(['ok' => false, 'error' => 'Could not find user to update.']);
    }

} catch (PDOException $e) {
    // If the database query fails for any reason
    echo json_encode(['ok' => false, 'error' => 'A database error occurred.']);
}

?>