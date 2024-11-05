<?php
session_start(); // Inicia a sessão

// Configurações do banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "espaco_vip_luciana";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Erro: Conexão falhou: " . $conn->connect_error]);
    exit;
}

$userId = $_SESSION['user_id']; // Pega o ID do usuário logado

// Busca os agendamentos do usuário
$query = $conn->prepare("SELECT * FROM agendamentos WHERE user_id = ?");
$query->bind_param("i", $userId);
$query->execute();
$result = $query->get_result();

$agendamentos = [];

while ($row = $result->fetch_assoc()) {
    // Busca os serviços adicionais relacionados ao agendamento
    $queryAdditional = $conn->prepare("SELECT * FROM servicos_adicionais WHERE agendamento_id = ?");
    $queryAdditional->bind_param("i", $row['id']);
    $queryAdditional->execute();
    $resultAdditional = $queryAdditional->get_result();

    $additionalServices = [];
    while ($additional = $resultAdditional->fetch_assoc()) {
        $additionalServices[] = [
            'service_name' => $additional['service_name'],
            'service_duration_price' => $additional['service_duration_price']
        ];
    }

    $agendamentos[] = [
        'id' => $row['id'],
        'service_name' => $row['service_name'],
        'service_duration_price' => $row['service_duration_price'],
        'service_date' => $row['service_date'],
        'service_time' => $row['service_time'],
        'additional_services' => $additionalServices
    ];
}

echo json_encode(['success' => true, 'agendamentos' => $agendamentos]);

$conn->close(); // Fecha a conexão
?>
