<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");  // Permite o acesso de qualquer origem

// Configuração de conexão com o banco de dados
$host = "localhost";
$usuario = "root"; // Seu usuário do MySQL
$senha = "nova_senha";       // Sua senha do MySQL
$dbname = "jogo_da_vida"; // Nome do seu banco de dados

// Criando conexão
$conn = new mysqli($host, $usuario, $senha, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Consulta para pegar as missões
$sql = "SELECT * FROM missoes";
$result = $conn->query($sql);

$missoes = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $missoes[] = $row;
    }
}

// Retorna o resultado em formato JSON
echo json_encode($missoes);

// Fecha a conexão
$conn->close();
?>
