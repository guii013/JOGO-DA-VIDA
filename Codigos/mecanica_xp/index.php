<?php

function calculateXp($playerId) {
    // Conectar ao banco de dados
    $conn = new mysqli('localhost', 'root', '', 'rpg_game');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Obter informações do jogador
    $sql = "SELECT * FROM players WHERE id = $playerId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $player = $result->fetch_assoc();
        $currentXp = $player['xp'];
        $level = $player['level'];

        // A cada missão concluída, ganhar XP suficiente para subir 1 nível inteiro
        // Aqui você define a quantidade de XP que corresponde a 1 nível
        $xpForNextLevel = 100; // Define que o jogador precisa de 100 XP para alcançar o próximo nível

        // Adicionar XP ao jogador pela missão concluída
        $currentXp += $xpForNextLevel;

        // Verificar se o jogador atingiu o próximo nível
        while ($currentXp >= 100) { // O jogador ganha 1 nível completo por missão
            $level++;
            $currentXp -= 100; // Subtrai 100 XP para o próximo nível
        }

        // Atualizar dados do jogador
        $sql = "UPDATE players SET level = $level, xp = $currentXp WHERE id = $playerId";
        $conn->query($sql);
    }

    $conn->close();
}
