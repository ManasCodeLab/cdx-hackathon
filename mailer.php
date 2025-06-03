<?php
require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Only define if not already defined
if (!defined('SMTP_HOST')) {
    $dotenv = parse_ini_file('.env');
    define('SMTP_HOST', $dotenv['SMTP_HOST'] ?? 'smtp.zoho.com');
    define('SMTP_PORT', $dotenv['SMTP_PORT'] ?? 465);
    define('SMTP_USER', $dotenv['SMTP_USER'] ?? 'admin@manas.eu.org');
    define('SMTP_PASS', $dotenv['SMTP_PASS'] ?? 'hH8JBABqNzmq');
    define('SMTP_FROM_EMAIL', $dotenv['SMTP_FROM_EMAIL'] ?? SMTP_USER);
    define('SMTP_FROM_NAME', $dotenv['SMTP_FROM_NAME'] ?? 'CodeGenX');
}

function sendConfirmationMail($toEmail, $toName, $subject, $bodyHtml) {
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;
        $mail->Password   = SMTP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = SMTP_PORT;

        // Recipients
        $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
        $mail->addAddress($toEmail, $toName);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $bodyHtml;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
} 