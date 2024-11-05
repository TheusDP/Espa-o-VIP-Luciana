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

$agendamentoId = $_POST['agendamento_id']; // Pega o ID do agendamento a ser excluído

// Exclui os serviços adicionais do agendamento
$queryDeleteAdditional = $conn->prepare("DELETE FROM servicos_adicionais WHERE agendamento_id = ?");
$queryDeleteAdditional->bind_param("i", $agendamentoId);
$queryDeleteAdditional->execute();

// Exclui o agendamento
$queryDelete = $conn->prepare("DELETE FROM agendamentos WHERE id = ?");
$queryDelete->bind_param("i", $agendamentoId);
$queryDelete->execute();

if ($queryDelete->affected_rows > 0) {
    echo json_encode(["success" => true, "message" => "Agendamento e serviços adicionais excluídos com sucesso!"]);
} else {
    echo json_encode(["success" => false, "message" => "Erro ao excluir agendamento."]);
}

$conn->close(); // Fecha a conexão
?>
