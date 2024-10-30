<?php
session_start(); // Inicia a sessão

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

// Processar o login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['email']); // Escapar strings
    $senha = $_POST['senha'];

    // Consultar usuário no banco de dados
    $sql = "SELECT * FROM usuarios WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verificar se a senha está correta
        if (password_verify($senha, $row['senha'])) {
            // Login bem-sucedido
            $_SESSION['user_id'] = $row['id'];
            header('Location: index.php'); // Redireciona para index.php
            exit(); // Garante que o script não continue após o redirecionamento
        } else {
            echo "Senha incorreta!";
        }
    } else {
        echo "Usuário não encontrado!";
    }
}

$conn->close();
?>