<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$conn = new mysqli('localhost', 'root', '', 'rpg_game');

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

$playerId = isset($_GET['playerId']) ? intval($_GET['playerId']) : 0;

$sql = "SELECT * FROM missions WHERE player_id = $playerId ORDER BY created_at DESC";
$result = $conn->query($sql);

$missions = [];
while ($row = $result->fetch_assoc()) {
    $missions[] = $row;
}

echo json_encode($missions);

$conn->close();
?>
