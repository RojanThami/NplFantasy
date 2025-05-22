<?php
// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Load PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// If you installed manually:
require 'libs/PHPMailer/src/Exception.php';
require 'libs/PHPMailer/src/PHPMailer.php';
require 'libs/PHPMailer/src/SMTP.php';

// Set response type
header('Content-Type: application/json');

// Start session to store OTP
session_start();

// Get JSON input from frontend
$data = json_decode(file_get_contents("php://input"), true);

// Check if email is provided
if (!isset($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid email address']);
    exit;
}

$email = $data['email'];
$otp = rand(100000, 999999);
$_SESSION['otp'] = $otp;
$_SESSION['email'] = $email;
$_SESSION['otp_expires'] = time() + 120; // expires in 2 minutes

// Send email via PHPMailer
$mail = new PHPMailer(true);

try {
    // SMTP configuration
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
   $mail->Username   = 'prazolstha12345@gmail.com'; // use your Gmail
    $mail->Password   = 'wylq wgww jujr rcoc';   // use App Password (not your Gmail password)
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    // Email content
    $mail->setFrom('yourgmail@gmail.com', 'Fantasy NPL');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = 'Your Fantasy NPL OTP Code';
    $mail->Body    = "<p>Hello,</p><p>Your OTP is: <strong>$otp</strong></p><p>It is valid for 2 minutes.</p>";

    $mail->send();

    echo json_encode(['status' => 'success', 'message' => 'OTP sent']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $mail->ErrorInfo]);
}
