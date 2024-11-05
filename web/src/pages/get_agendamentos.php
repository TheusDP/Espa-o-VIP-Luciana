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

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Usuário não autenticado."]);
    exit;
}

$userId = $_SESSION['user_id'];

// Consulta para obter os agendamentos principais do usuário
$query = "SELECT * FROM agendamentos WHERE user_id = ? ORDER BY service_date DESC, service_time DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$agendamentos = [];
while ($row = $result->fetch_assoc()) {
    // Adiciona o agendamento principal à lista
    $agendamento = [
        'service_name' => $row['service_name'],
        'service_duration_price' => $row['service_duration_price'],
        'service_date' => $row['service_date'],
        'service_time' => $row['service_time'],
        'address' => $row['address'],
        'additional_services' => [] // Inicia uma lista vazia para serviços adicionais
    ];

    // Consulta para obter os serviços adicionais relacionados a este agendamento
    $queryAdditionalServices = "SELECT * FROM servicos_adicionais WHERE agendamento_id = ?";
    $stmtAdditional = $conn->prepare($queryAdditionalServices);
    $stmtAdditional->bind_param("i", $row['id']);
    $stmtAdditional->execute();
    $resultAdditional = $stmtAdditional->get_result();

    // Adiciona os serviços adicionais ao agendamento
    while ($additionalService = $resultAdditional->fetch_assoc()) {
        $agendamento['additional_services'][] = [
            'service_name' => $additionalService['service_name'],
            'service_duration_price' => $additionalService['service_duration_price'],
            'service_date' => $additionalService['service_date'],
            'service_time' => $additionalService['service_time'],
            'address' => $additionalService['address']
        ];
    }

    // Adiciona o agendamento (principal + adicionais) à lista
    $agendamentos[] = $agendamento;

    $stmtAdditional->close();
}

$stmt->close();
$conn->close();

// Verifica se há agendamentos e retorna os dados
if (count($agendamentos) > 0) {
    echo json_encode(["success" => true, "agendamentos" => $agendamentos]);
} else {
    echo json_encode(["success" => false, "message" => "Nenhum agendamento encontrado."]);
}
?>
