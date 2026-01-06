<?php
// ALWAYS include the Composer autoloader first!
require 'vendor/autoload.php';

// Import the PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Set the content type to JSON for all responses
header('Content-Type: application/json');

// --- The rest of your code stays the same ---

// --- Database Connection (replace with your own) ---
$host = 'localhost';
$dbname = 'voiceapp';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Send a JSON error if the database connection fails
    echo json_encode(['ok' => false, 'error' => 'Database connection failed.']);
    exit();
}
// --- End Database Connection ---


// Get the email from the app's request
$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'] ?? '';

if (empty($email)) {
    echo json_encode(['ok' => false, 'error' => 'Email is required.']);
    exit();
}

// Generate a new OTP and set its expiry time (e.g., 15 minutes from now)
$otp = rand(100000, 999999);
$otp_expiry = date('Y-m-d H:i:s', strtotime('+15 minutes'));

// Save the new OTP to the database for the user
$stmt = $pdo->prepare("UPDATE users SET verification_code = ?, verification_expiry = ? WHERE email = ?");
$stmt->execute([$otp, $otp_expiry, $email]);

// --- Send the email using PHPMailer ---
$mail = new PHPMailer(true);

try {
    // Your Gmail SMTP server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'YOUR_GMAIL_ADDRESS@gmail.com'; // <-- REPLACE THIS
    $mail->Password   = 'YOUR_GMAIL_APP_PASSWORD';      // <-- REPLACE THIS
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;

    //Recipients
    $mail->setFrom('YOUR_GMAIL_ADDRESS@gmail.com', 'Voice Command AI App');
    $mail->addAddress($email);

    // Email Content
    $mail->isHTML(true);
    $mail->Subject = 'Your New Verification Code';
    $mail->Body    = "Your new verification code is: <b>$otp</b>";

    $mail->send();

    // If successful, send a JSON success response
    echo json_encode(['ok' => true, 'message' => 'Verification code sent again.']);

} catch (Exception $e) {
    // If it fails, send a JSON error
    echo json_encode(['ok' => false, 'error' => "Mailer Error: {$mail->ErrorInfo}"]);
}

?>