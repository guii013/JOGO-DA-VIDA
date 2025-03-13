<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$host = "localhost";
$usuario = "root";
$senha = "nova_senha";
$dbname = "jogo_da_vida";

// Conectar ao banco
$conn = new mysqli($host, $usuario, $senha, $dbname);
if ($conn->connect_error) {
    die(json_encode(["error" => "Conexão falhou: " . $conn->connect_error]));
}

// Recebe os dados enviados pelo React
$data = json_decode(file_get_contents("php://input"), true);

// Verifica se o ID foi enviado corretamente
if (!isset($data["id"])) {
    echo json_encode(["error" => "ID da missão não informado"]);
    exit();
}

$id = intval($data["id"]);

// Remove a missão do banco de dados
$sql = "DELETE FROM missoes WHERE id = $id";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["message" => "Missão removida com sucesso"]);
} else {
    echo json_encode(["error" => "Erro ao remover missão: " . $conn->error]);
}

$conn->close();
?>
