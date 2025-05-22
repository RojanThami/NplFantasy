<?php
header('Content-Type: application/json');
session_start();

$data = json_decode(file_get_contents("php://input"), true);

// Validate input
if (!isset($data['otp'])) {
    echo json_encode(['status' => 'error', 'message' => 'OTP is required']);
    exit;
}

$enteredOtp = $data['otp'];
$storedOtp = $_SESSION['otp'] ?? null;
$otpExpires = $_SESSION['otp_expires'] ?? 0;

if (!$storedOtp) {
    echo json_encode(['status' => 'error', 'message' => 'No OTP found. Please request a new one.']);
    exit;
}

// Check expiry
if (time() > $otpExpires) {
    echo json_encode(['status' => 'error', 'message' => 'OTP has expired.']);
    exit;
}

// Validate OTP
if ($enteredOtp == $storedOtp) {
    // You could also store user in DB here
    unset($_SESSION['otp'], $_SESSION['otp_expires']);
    echo json_encode(['status' => 'success', 'message' => 'OTP verified']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid OTP']);
}
