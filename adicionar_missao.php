<?php
header("Content-Type: application/json; charset=UTF-8");
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

// Verifica se os dados chegaram corretamente
if (!isset($data["titulo"]) || !isset($data["descricao"])) {
    echo json_encode(["error" => "Dados inválidos"]);
    exit();
}

$titulo = $conn->real_escape_string($data["titulo"]);
$descricao = $conn->real_escape_string($data["descricao"]);

// Verifica se os campos estão vazios
if (empty($titulo) || empty($descricao)) {
    echo json_encode(["error" => "Título e descrição são obrigatórios"]);
    exit();
}

// Insere a missão no banco
$sql = "INSERT INTO missoes (titulo, descricao) VALUES ('$titulo', '$descricao')";
if ($conn->query($sql) === TRUE) {
    echo json_encode(["message" => "Missão adicionada com sucesso"]);
} else {
    echo json_encode(["error" => "Erro ao adicionar missão: " . $conn->error]);
}

$conn->close();
?>
