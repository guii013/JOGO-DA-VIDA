<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Configuração de conexão com o banco de dados
$host = "localhost";
$usuario = "root"; // Seu usuário do MySQL
$senha = "nova_senha"; // Sua senha do MySQL
$dbname = "jogo_da_vida"; // Nome do seu banco de dados

// Criando conexão
$conn = new mysqli($host, $usuario, $senha, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die(json_encode(["error" => "Falha na conexão: " . $conn->connect_error]));
}

// Recebendo os dados do corpo da requisição
$data = json_decode(file_get_contents("php://input"));
$missao_id = $data->missao_id;
$progresso = $data->progresso;

// Atualizando o progresso total da missão
$sql = "UPDATE missoes SET progresso_total = progresso_total + ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("di", $progresso, $missao_id);

if ($stmt->execute()) {
    echo json_encode(["message" => "Progresso atualizado com sucesso"]);
} else {
    echo json_encode(["error" => "Erro ao atualizar progresso"]);
}

// Fecha a conexão
$conn->close();
?>
