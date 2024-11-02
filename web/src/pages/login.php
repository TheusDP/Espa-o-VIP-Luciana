<?php
session_start();

// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "espaco_vip_luciana";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Processar o login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $conn->real_escape_string($_POST['nome']); // Obter o nome do usuário
    $senha = $_POST['senha'];

    // Verificar se a senha termina com '#adm' para identificar administrador
    $isAdmin = substr($senha, -4) === '#adm';
    $senhaOriginal = $isAdmin ? substr($senha, 0, -4) : $senha;

    // Consultar usuário no banco de dados
    $sql = "SELECT * FROM usuarios WHERE nome='$nome'"; // Alterado para buscar pelo nome
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verificar se a senha do usuário está correta
        if (password_verify($senhaOriginal, $row['senha'])) {
            // Definir a sessão com base no tipo de acesso
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['role'] = $isAdmin ? 'admin' : 'user'; // Define a role como 'admin' ou 'user'
            $_SESSION['nome_usuario'] = $row['nome']; // Armazenar o nome do usuário na sessão

            // Redirecionar para a página correta
            $redirectPage = $isAdmin ? 'admin_login.php' : 'index.php';
            header("Location: $redirectPage");
            exit();
        } else {
            echo "Senha incorreta!";
        }
    } else {
        echo "Usuário não encontrado!";
    }
}

$conn->close();
?>
