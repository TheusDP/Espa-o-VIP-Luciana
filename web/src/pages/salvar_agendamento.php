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

// Verifica se os dados foram enviados via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se todos os campos obrigatórios estão presentes
    if (isset($_POST['serviceName'], $_POST['serviceDurationPrice'], $_POST['serviceDate'], $_POST['serviceTime'], $_POST['serviceAddress'])) {
        // Obtém os dados do serviço principal
        $serviceName = $_POST['serviceName'];
        $serviceDurationPrice = $_POST['serviceDurationPrice'];
        $serviceDate = $_POST['serviceDate'];
        $serviceTime = $_POST['serviceTime'];
        $serviceAddress = $_POST['serviceAddress'];
        $userId = $_SESSION['user_id'];

        // Verifica se o serviço principal já foi agendado
        $queryCheck = $conn->prepare("SELECT * FROM agendamentos WHERE user_id = ? AND service_name = ? AND service_date = ? AND service_time = ?");
        $queryCheck->bind_param("isss", $userId, $serviceName, $serviceDate, $serviceTime);
        $queryCheck->execute();
        $resultCheck = $queryCheck->get_result();

        if ($resultCheck->num_rows > 0) {
            echo json_encode(["success" => false, "message" => "Erro: Este serviço já foi agendado para a data e horário selecionados."]);
            exit;
        }

        // Prepara a declaração SQL para salvar o serviço principal
        $stmt = $conn->prepare("INSERT INTO agendamentos (user_id, service_name, service_duration_price, service_date, service_time, address) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss", $userId, $serviceName, $serviceDurationPrice, $serviceDate, $serviceTime, $serviceAddress);

        if ($stmt->execute()) {
            $agendamentoId = $stmt->insert_id;

            // Salva os serviços adicionais, se houver
            if (isset($_POST['additionalServices']) && !empty($_POST['additionalServices'])) {
                $additionalServices = json_decode($_POST['additionalServices'], true); // Decodifica o JSON

                foreach ($additionalServices as $additionalService) {
                    $additionalName = $additionalService['name'];
                    $additionalPrice = isset($additionalService['price']) ? $additionalService['price'] : 'Preço não definido';

                    // Verifica se o serviço adicional já foi agendado
                    $queryCheckAdditional = $conn->prepare("SELECT * FROM servicos_adicionais WHERE user_id = ? AND service_name = ? AND service_date = ? AND service_time = ?");
                    $queryCheckAdditional->bind_param("isss", $userId, $additionalName, $serviceDate, $serviceTime);
                    $queryCheckAdditional->execute();
                    $resultCheckAdditional = $queryCheckAdditional->get_result();

                    if ($resultCheckAdditional->num_rows > 0) {
                        // Ignora este serviço adicional e continua com os demais
                        continue; 
                    }

                    // Prepara a declaração para salvar serviços adicionais
                    $stmtAdditional = $conn->prepare("INSERT INTO servicos_adicionais (agendamento_id, user_id, service_name, service_duration_price, service_date, service_time, address) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmtAdditional->bind_param("iisssss", $agendamentoId, $userId, $additionalName, $additionalPrice, $serviceDate, $serviceTime, $serviceAddress);
                    $stmtAdditional->execute();
                    $stmtAdditional->close();
                }
            }

            // Retorna sucesso
            echo json_encode(["success" => true, "message" => "Agendamento realizado com sucesso!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Erro ao agendar: " . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Erro: Falta dados obrigatórios."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Método inválido."]);
}

$conn->close(); // Fecha a conexão
?>
