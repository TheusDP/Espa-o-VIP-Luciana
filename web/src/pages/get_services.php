<?php
// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "espaco_vip_luciana";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifique a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Consulta para obter os serviços
$sql = "SELECT nome, duracao, preco FROM servicos";
$result = $conn->query($sql);

$servicos = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $servicos[] = $row;
    }
}

$conn->close();
?>
