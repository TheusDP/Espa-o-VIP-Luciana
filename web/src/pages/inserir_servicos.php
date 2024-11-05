<?php
// Conexão com o banco de dados
$servername = "localhost";
$username = "root"; // Substitua pelo seu nome de usuário do MySQL
$password = ""; // Substitua pela sua senha do MySQL
$dbname = "espaco_vip_luciana";// Substitua pelo nome do banco de dados

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Verificar se já existem dados na tabela
$sqlCheck = "SELECT COUNT(*) AS total FROM servicos";
$result = $conn->query($sqlCheck);
$row = $result->fetch_assoc();

if ($row['total'] > 0) {
} else {
    // Serviços a serem inseridos
    $servicos = [
        ["Corte com Escova", "1 - 2h", "R$ 90", "Rua Sarandi, 22, Vila Bela"],
        ["Tintura com Escova", "2h", "R$ 140", "Rua Sarandi, 22, Vila Bela"],
        ["Mecha/Luzes", "5 - 7h", "A partir de R$ 400", "Rua Sarandi, 22, Vila Bela"],
        ["Progressiva", "4 - 5h", "A partir de R$ 400", "Rua Sarandi, 22, Vila Bela"],
        ["Botox/Celagem", "3 - 4h", "A partir de R$ 150", "Rua Sarandi, 22, Vila Bela"],
        ["Tratamentos", "2 - 3h", "A partir de R$ 100", "Rua Sarandi, 22, Vila Bela"]
    ];

    // Inserir cada serviço na tabela
    foreach ($servicos as $servico) {
        $nome = $servico[0];
        $duracao = $servico[1];
        $preco = $servico[2];
        $endereco = $servico[3];

        $sql = "INSERT INTO servicos (nome, duracao, preco, endereco) VALUES ('$nome', '$duracao', '$preco', '$endereco')";

        if ($conn->query($sql) === TRUE) {
            echo "Serviço '$nome' inserido com sucesso.<br>";
        } else {
            echo "Erro ao inserir serviço '$nome': " . $conn->error . "<br>";
        }
    }
}

// Fechar conexão
$conn->close();
?>
