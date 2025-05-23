<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');

include_once("../config/db.php");

$sql = "SELECT * FROM players";
$stmt = $conn->prepare($sql);
$stmt->execute();

$players = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($players);
?>
