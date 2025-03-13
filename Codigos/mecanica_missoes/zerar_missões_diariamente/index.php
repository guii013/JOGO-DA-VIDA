<?php

function resetMissions() {
    $conn = new mysqli('localhost', 'root', '', 'rpg_game');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Zerar as missões completadas
    $sql = "UPDATE missions SET completed = 0 WHERE completed = 1";
    $conn->query($sql);

    // Atualizar a data de expiração das missões
    $sql = "UPDATE missions SET deadline = NOW() + INTERVAL 1 DAY WHERE deadline < NOW()";
    $conn->query($sql);

    $conn->close();
}
