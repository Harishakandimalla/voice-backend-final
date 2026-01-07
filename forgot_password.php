<?php
header('Content-Type: application/json');

require __DIR__ . '/db.php';
require __DIR__ . '/vendor/autoload.php';
$config = require __DIR__ . '/mail_config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/* ---------- INPUT ---------- */
$data = json_decode(file_get_contents("php://input"), true);
$email = trim($data['email'] ?? '');

/* ---------- VALIDATION ---------- */
if ($email === '') {
    echo json_encode(["ok"=>false,"error"=>"email_required"]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["ok"=>false,"error"=>"invalid_email"]);
    exit;
}

/* ---------- CHECK EMAIL ---------- */
$stmt = $pdo->prepare("SELECT id FROM `register` WHERE email=?");
$stmt->execute([$email]);

if (!$stmt->fetch()) {
    echo json_encode(["ok"=>false,"error"=>"email_not_found"]);
    exit;
}

/* ---------- GENERATE OTP ---------- */
$otp = rand(100000, 999999);
$expiry = date("Y-m-d H:i:s", strtotime("+10 minutes"));

/* ---------- SAVE OTP ---------- */
$update = $pdo->prepare("
    UPDATE `register`
    SET reset_otp=?, reset_otp_expiry=?
    WHERE email=?
");
$update->execute([$otp, $expiry, $email]);

/* ---------- SEND EMAIL ---------- */
try {
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host       = $config->smtp->host;
    $mail->SMTPAuth   = true;
    $mail->Username   = $config->smtp->username;
    $mail->Password   = $config->smtp->password;
    $mail->SMTPSecure = $config->smtp->secure;
    $mail->Port       = $config->smtp->port;

    $mail->setFrom($config->from_email, $config->from_name);
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = "Password Reset OTP";
    $mail->Body = "
        <h3>Password Reset</h3>
        <p>Your OTP is:</p>
        <h2>$otp</h2>
        <p>Valid for 10 minutes</p>
    ";

    $mail->send();

    echo json_encode(["ok"=>true,"message"=>"otp_sent"]);

} catch (Exception $e) {
    echo json_encode(["ok"=>false,"error"=>"email_sending_failed"]);
}
