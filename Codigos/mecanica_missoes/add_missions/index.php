<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

$conn = new mysqli('localhost', 'root', '', 'rpg_game');

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['playerId'], $data['title'], $data['description'], $data['deadline'], $data['xpReward'])) {
    die(json_encode(["error" => "Dados incompletos"]));
}

$playerId = intval($data['playerId']);
$title = $conn->real_escape_string($data['title']);
$description = $conn->real_escape_string($data['description']);
$deadline = $conn->real_escape_string($data['deadline']);
$xpReward = intval($data['xpReward']);

$sql = "INSERT INTO missions (player_id, title, description, deadline, xp_reward) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isssi", $playerId, $title, $description, $deadline, $xpReward);

if ($stmt->execute()) {
    echo json_encode(["success" => "Missão adicionada com sucesso"]);
} else {
    echo json_encode(["error" => "Erro ao adicionar missão"]);
}

$stmt->close();
$conn->close();
?>
