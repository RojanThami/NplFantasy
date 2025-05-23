<?php
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');

include_once("../config/db.php");

$apiUrl = "https://nepal-premiere-league-npl-api.vercel.app/api/npl/teams";

// Fetch data from API
$response = file_get_contents($apiUrl);
if ($response === false) {
    echo json_encode(["message" => "Failed to fetch API"]);
    exit;
}

$teams = json_decode($response, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(["message" => "Invalid JSON response"]);
    exit;
}

$roles = ["Batsman", "Bowler", "All-Rounder", "Wicket-Keeper"];
$inserted = 0;

foreach ($teams as $team) {
    if (!isset($team['name']) || !isset($team['players'])) continue;

    $teamName = $team['name'];
    $players = $team['players'];

    foreach ($players as $playerName) {
        if (!is_string($playerName)) continue;

        $role = $roles[array_rand($roles)];
        $price = rand(6, 10);

        $sql = "INSERT INTO players (name, role, team, price) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$playerName, $role, $teamName, $price]);

        $inserted++;
    }
}

echo json_encode(["message" => "$inserted players imported successfully."]);
?>
