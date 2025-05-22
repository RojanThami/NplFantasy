<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include_once("../config/db.php");

$sql = "SELECT * FROM players";
$stmt = $conn->prepare($sql);
$stmt->execute();

$players = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($players);
?>
