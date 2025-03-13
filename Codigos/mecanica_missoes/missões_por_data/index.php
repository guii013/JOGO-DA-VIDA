<?php

function filterMissionsByDate($playerId, $startDate, $endDate) {
    $conn = new mysqli('localhost', 'root', '', 'rpg_game');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Filtrar missões por data
    $sql = "SELECT * FROM missions WHERE player_id = $playerId AND created_at BETWEEN '$startDate' AND '$endDate'";
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
