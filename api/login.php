<?php
header('Content-Type: application/json');
session_start();

$data = json_decode(file_get_contents("php://input"), true);
$email = trim($data['email'] ?? '');
$password = trim($data['password'] ?? '');

if (!$email || !$password) {
    echo json_encode(['status' => 'error', 'message' => 'Email and password required']);
    exit;
}

// DB credentials
$host = 'localhost';
$db = 'your_database';  // ✅ Replace
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'DB connection failed']);
    exit;
}

// Check user
$stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['status' => 'error', 'message' => 'User not found']);
    exit;
}

$user = $result->fetch_assoc();
if (!password_verify($password, $user['password'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid password']);
    exit;
}

// Set login session
$_SESSION['user_id'] = $user['id'];
$_SESSION['user_name'] = $user['name'];
$_SESSION['email'] = $email;

echo json_encode(['status' => 'success', 'message' => 'Login successful', 'name' => $user['name']]);
