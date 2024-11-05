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

// Processar formulário de cadastro 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $conn->real_escape_string($_POST['nome']);
    $email = $conn->real_escape_string($_POST['email']);
    $senha = $_POST['senha'];

    // Verificar se a senha termina com '#adm' para identificar administrador
    $isAdmin = substr($senha, -4) === '#adm';
    $senhaOriginal = $isAdmin ? substr($senha, 0, -4) : $senha; // Remove '#adm' se for admin
    $senhaHash = password_hash($senhaOriginal, PASSWORD_DEFAULT); // Criptografa a senha

    // Definir o tipo de usuário
    $role = $isAdmin ? 'admin' : 'user';

    // Inserir dados na tabela de usuários
    $sql = "INSERT INTO usuarios (nome, email, senha, role) VALUES ('$nome', '$email', '$senhaHash', '$role')";

    // Executar a consulta
    if (mysqli_query($conn, $sql)) {
        // Definir mensagem de sucesso na sessão
        $_SESSION['message'] = 'Cadastro realizado com sucesso!';
        $_SESSION['message_type'] = 'success'; // Tipo de mensagem
        // Redirecionar após o cadastro
        header('Location: index.php');
        exit();
    } else {
        // Definir mensagem de erro na sessão
        $_SESSION['message'] = 'Erro ao cadastrar usuário: ' . mysqli_error($conn);
        $_SESSION['message_type'] = 'error'; // Tipo de mensagem
        // Redirecionar após o erro
        header('Location: index.php');
        exit();
    }
}

$conn->close();
?>
