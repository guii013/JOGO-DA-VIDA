<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

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

        // Calcular o XP necessário para o próximo nível
        $xpForNextLevel = $level * 100;

        // Verificar se o jogador alcançou o próximo nível
        if ($currentXp >= $xpForNextLevel) {
            $level++;
            $currentXp -= $xpForNextLevel; // Resetar XP para o próximo nível
        }

        // Atualizar dados do jogador
        $sql = "UPDATE players SET level = $level, xp = $currentXp WHERE id = $playerId";
        $conn->query($sql);
    }

    $conn->close();
}

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


header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$conn = new mysqli('localhost', 'root', '', 'rpg_game');

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

$playerId = isset($_GET['playerId']) ? intval($_GET['playerId']) : 0;

$sql = "SELECT * FROM missions WHERE player_id = $playerId ORDER BY created_at DESC";
$result = $conn->query($sql);

$missions = [];
while ($row = $result->fetch_assoc()) {
    $missions[] = $row;
}

echo json_encode($missions);

$conn->close();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

$conn = new mysqli('localhost', 'root', '', 'rpg_game');

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['playerId'], $data['title'], $data['description'], $data['deadline'], $data['xpReward'])) {
    die(json_encode(["error" => "Dados incompletos"]));
}

$playerId = intval($data['playerId']);
$title = $conn->real_escape_string($data['title']);
$description = $conn->real_escape_string($data['description']);
$deadline = $conn->real_escape_string($data['deadline']);
$xpReward = intval($data['xpReward']);

$sql = "INSERT INTO missions (player_id, title, description, deadline, xp_reward) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isssi", $playerId, $title, $description, $deadline, $xpReward);

if ($stmt->execute()) {
    echo json_encode(["success" => "Missão adicionada com sucesso"]);
} else {
    echo json_encode(["error" => "Erro ao adicionar missão"]);
}

$stmt->close();
$conn->close();



$host = "localhost";
$user = "root"; // ou outro usuário do MySQL
$pass = "nova_senha"; // senha, se tiver
$dbname = "jogo_da_vida"; // altere para o nome correto do seu banco

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
} else {
    echo "Conectado ao banco de dados com sucesso!";
}
?>




