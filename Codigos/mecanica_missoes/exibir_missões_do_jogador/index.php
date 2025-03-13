<?php

function getMissions($playerId) {
    $conn = new mysqli('localhost', 'root', '', 'rpg_game');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Obter missões do jogador
    $sql = "SELECT * FROM missions WHERE player_id = $playerId ORDER BY created_at DESC";
    $result = $conn->query($sql);

    $missions = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $missions[] = $row;
        }
    }

    $conn->close();
    return $missions;
}
