<?php
// concluir_missao.php
require 'db_connection.php'; // Arquivo de conexão com o banco

// Pega os dados da requisição
$missao_id = $_POST['missao_id']; // ID da missão
$player_id = $_POST['player_id']; // ID do jogador
$xp_ganho = $_POST['xp']; // XP ganho com a missão

// Verifica o jogador
$query = "SELECT * FROM jogadores WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $player_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $player = $result->fetch_assoc();

    // Adiciona XP ao jogador
    $new_xp = $player['xp_atual'] + $xp_ganho;
    
    // Verifica se o jogador sobe de nível
    $new_level = $player['nivel'];
    while ($new_xp >= $player['xp_necessario']) {
        $new_xp -= $player['xp_necessario'];
        $new_level++;
        $player['xp_necessario'] = $new_level * 100; // Calcula o XP necessário para o próximo nível
    }

    // Atualiza os dados do jogador
    $update_query = "UPDATE jogadores SET xp_atual = ?, nivel = ?, xp_necessario = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("iiii", $new_xp, $new_level, $player['xp_necessario'], $player_id);
    $stmt->execute();

    echo json_encode(['message' => 'Missão concluída, XP adicionado!', 'xp_atual' => $new_xp, 'nivel' => $new_level]);
} else {
    echo json_encode(['message' => 'Jogador não encontrado']);
}
?>
