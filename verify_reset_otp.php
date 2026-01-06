<?php
// Always start by including the Composer autoloader
require 'vendor/autoload.php';

// Tell the app and browser that this script will always return JSON
header('Content-Type: application/json');

// --- Database Connection ---
$host = 'localhost';
$dbname = 'voiceapp'; // Make sure this is your correct database name
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


// --- Start of Verification Logic ---
$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'] ?? '';
$otp = $data['otp'] ?? '';

// 1. Basic validation
if (empty($email) || empty($otp)) {
    echo json_encode(['ok' => false, 'error' => 'Email and OTP are required.']);
    exit();
}

// 2. Fetch the user's stored OTP and its expiry date from the database
$stmt = $pdo->prepare("SELECT verification_code, verification_expiry FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// 3. Check if the user exists or if there's a code to check
if (!$user || empty($user['verification_code'])) {
    echo json_encode(['ok' => false, 'error' => 'Invalid OTP. Please request a new code.']);
    exit();
}

// 4. Check if the OTP has expired
if (strtotime($user['verification_expiry']) < time()) {
    echo json_encode(['ok' => false, 'error' => 'Your verification code has expired.']);
    exit();
}

// 5. THE CRITICAL CHECK: Compare the user's input with the database value
if ($user['verification_code'] != $otp) {
    // This is the line that is likely failing in your old script.
    // It sends the "invalid_otp" error.
    echo json_encode(['ok' => false, 'error' => 'Invalid OTP.']);
    exit();
}

// 6. SUCCESS! Now, invalidate the OTP so it cannot be used again.
// This is a critical security step to prevent replay attacks.
$updateStmt = $pdo->prepare("UPDATE users SET verification_code = NULL, verification_expiry = NULL WHERE email = ?");
$updateStmt->execute([$email]);

// 7. Finally, send the success response to the Android app.
echo json_encode(['ok' => true, 'message' => 'verified_success']);

?>