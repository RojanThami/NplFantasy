<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'libs/PHPMailer/src/Exception.php';
require 'libs/PHPMailer/src/PHPMailer.php';
require 'libs/PHPMailer/src/SMTP.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$email = $data['email'];

$otp = rand(100000, 999999);
session_start();
$_SESSION['otp'] = $otp;  // Store OTP in session

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'prazolstha12345@gmail.com'; // use your Gmail
    $mail->Password   = 'wylq wgww jujr rcoc';   // use App Password (not your Gmail password)
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    // Recipients
    $mail->setFrom('yourgmail@gmail.com', 'Fantasy NPL');
    $mail->addAddress($email);

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Your Fantasy NPL OTP Code';
    $mail->Body    = "Your OTP is <strong>$otp</strong>. It will expire in 2 minutes.";

    $mail->send();
    echo json_encode(['status' => 'success', 'message' => 'OTP sent']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $mail->ErrorInfo]);
}
