<?php
// mailer.php

require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load mail configuration
$config = require __DIR__ . '/mail_config.php';

function getMailer(): PHPMailer
{
    global $config;

    $mail = new PHPMailer(true);

    // SMTP settings (SendGrid)
    $mail->isSMTP();
    $mail->Host       = $config['smtp']['host'];
    $mail->SMTPAuth   = true;

    // ✅ FIXED HERE
    //$mail->Username   = $config['smtp']['apikey']; // "apikey"
   // $mail->Password   = 
   // $mail->Port       = $config['smtp']['port'];
    //$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;//

    // Sender
    $mail->setFrom(
        $config['from_email'],
        $config['from_name']
    );

    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';

    return $mail;
}
