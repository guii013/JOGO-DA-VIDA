<?php

function addMission($playerId, $title, $description, $deadline, $xpReward) {
    $conn = new mysqli('localhost', 'root', '', 'rpg_game');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Inserir missão no banco de dados
    $stmt = $conn->prepare("INSERT INTO missions (player_id, title, description, deadline, xp_reward) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param('isssi', $playerId, $title, $description, $deadline, $xpReward);
    $stmt->execute();

    $stmt->close();
    $conn->close();
}
