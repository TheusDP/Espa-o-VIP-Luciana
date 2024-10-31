<?php
// Configurações do banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "espaco_vip_luciana";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verifica se os dados foram enviados via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se todos os campos obrigatórios estão presentes
    if (isset($_POST['serviceName'], $_POST['serviceDurationPrice'], $_POST['serviceDate'], $_POST['serviceTime'], $_POST['serviceAddress'])) {
        $serviceName = $_POST['serviceName'];
        $serviceDurationPrice = $_POST['serviceDurationPrice'];
        $serviceDate = $_POST['serviceDate'];
        $serviceTime = $_POST['serviceTime'];
        $address = $_POST['serviceAddress'];

        // Prepara a declaração SQL
        $stmt = $conn->prepare("INSERT INTO agendamentos (service_name, service_duration_price, service_date, service_time, address) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $serviceName, $serviceDurationPrice, $serviceDate, $serviceTime, $address);

        // Executa a declaração
        if ($stmt->execute()) {
            // Exibe a data formatada após o agendamento ser salvo
            $result = $conn->query("SELECT DATE_FORMAT(service_date, '%d de %M de %Y') AS formattedDate FROM agendamentos WHERE service_name = '$serviceName' ORDER BY id DESC LIMIT 1");
            if ($row = $result->fetch_assoc()) {
                echo "Agendamento salvo com sucesso para a data " . $row['formattedDate'];
            } else {
                echo "Agendamento salvo com sucesso!";
            }
        } else {
            echo "Erro ao salvar o agendamento: " . $stmt->error;
        }

        // Fecha a declaração
        $stmt->close();
    } else {
        echo "Erro: Campos obrigatórios faltando no formulário.";
    }
}

// Fecha a conexão
$conn->close();
?>
