<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');

include_once("../config/db.php");

// Read JSON input
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data) || !is_array($data)) {
    echo json_encode(["message" => "Invalid input"]);
    exit;
}

$inserted = 0;

foreach ($data as $player) {
    if (isset($player['name'], $player['role'], $player['team'])) {
        $sql = "INSERT INTO players (name, role, team, price) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            $player['name'],
            $player['role'],
            $player['team'],
            rand(6, 10) // Random price for fantasy logic
        ]);
        $inserted++;
    }
}

echo json_encode(["message" => "$inserted players inserted successfully."]);
?>
