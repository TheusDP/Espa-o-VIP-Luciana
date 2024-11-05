<?php
session_start();
header('Content-Type: application/json'); // Define o tipo de conteúdo como JSON

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "espaco_vip_luciana";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Conexão falhou: ' . $conn->connect_error]);
    exit;
}

// Obtém o user_id da sessão
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit;
}

$user_id = $_SESSION['user_id']; // Agora pegamos o user_id da sessão

// Consulta para obter agendamentos e serviços adicionais
$sql = "
    SELECT 
        ag.*,
        sa.service_name AS additional_service_name,
        sa.service_duration_price AS additional_service_duration_price
    FROM 
        agendamentos ag
    LEFT JOIN 
        servicos_adicionais sa ON ag.id = sa.agendamento_id
    WHERE 
        ag.user_id = ?
";

$stmt = $conn->prepare($sql);

if ($stmt === false) {
    echo json_encode(['success' => false, 'message' => 'Erro na preparação da consulta: ' . $conn->error]);
    exit;
}

$stmt->bind_param("i", $user_id); // Mudamos para 'i' se user_id for um inteiro
$stmt->execute();
$result = $stmt->get_result();

$agendamentos = [];
while ($row = $result->fetch_assoc()) {
    $agendamentos[] = $row;
}

$stmt->close();
$conn->close();

// Retorna os agendamentos em formato JSON
echo json_encode(['success' => true, 'agendamentos' => $agendamentos]);
?>
