<?php
// Conexão com o banco de dados
$servername = "localhost"; // Altere para o seu servidor
$username = "root"; // Altere para seu usuário do banco de dados
$password = ""; // Altere para sua senha do banco de dados
$dbname = "espaco_vip_luciana"; // Altere para o nome do seu banco de dados

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Processar formulário de cadastro
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $conn->real_escape_string($_POST['nome']);
    $email = $conn->real_escape_string($_POST['email']);
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Criptografa a senha

    // Inserir dados na tabela de usuários
    $sql = "INSERT INTO usuarios (nome, email, senha) VALUES ('$nome', '$email', '$senha')";

    // Executar a consulta
    if (mysqli_query($conn, $sql)) {
        // Redirecionar após o cadastro
        header('Location: index.php');
        exit();
    } else {
        echo "Erro ao cadastrar usuário: " . mysqli_error($conn);
    }
}

$conn->close();
?>