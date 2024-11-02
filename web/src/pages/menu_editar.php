<?php 
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

// Atualizar serviço no banco de dados
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_service"])) {
    $id = $_POST["id"];
    $nome = $_POST["nome"];
    $duracao = $_POST["duracao"];
    $preco = $_POST["preco"];

    $sql = "UPDATE servicos SET nome='$nome', duracao='$duracao', preco='$preco' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Serviço atualizado com sucesso!');</script>";
    } else {
        echo "<script>alert('Erro ao atualizar serviço: " . $conn->error . "');</script>";
    }
}

// Obter todos os serviços
$sql = "SELECT * FROM servicos";
$result = $conn->query($sql);

// Verifique se a consulta foi bem-sucedida
if (!$result) {
    die("Erro ao obter serviços: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Menu de Procedimentos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f0e6;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
        }
        .header a {
            text-decoration: none;
            color: #6a0dad;
            font-size: 16px;
        }
        .header a:hover {
            text-decoration: underline;
        }
        h1 {
            font-family: 'Pacifico', cursive;
            font-size: 24px; /* Defina o tamanho da fonte desejado */
            text-align: center;
        }
        .title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .treatment-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .treatment-card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 30%;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .treatment-card h2 {
            font-size: 20px;
            font-weight: bold;
            color: #000;
            margin-bottom: 10px;
        }
        .treatment-card p {
            font-size: 16px;
            color: #000;
            margin: 5px 0;
        }
        .treatment-card button {
            background-color: #000;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }
        .treatment-card button:hover {
            background-color: #333;
        }
        .popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }
        .popup-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            width: 400px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .popup-content .close {
            font-size: 20px;
            cursor: pointer;
            text-align: right;
        }
        .popup-content h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .popup-content form {
            display: flex;
            flex-direction: column;
        }
        .popup-content input {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .popup-content button[type="submit"] {
        background-color: #000;
        color: #fff;
        border: none;
        cursor: pointer;
        padding: 15px 20px;
        font-size: 18px; 
        border-radius: 5px;
    }
    .popup-content button[type="submit"]:hover {
        background-color: #333;
    }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="admin_login.php"><i class="fas fa-arrow-left"></i> Voltar</a>
        </div>
        <h1>Editar Menu de Procedimentos</h1>
        <div class="treatment-grid">
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<div class='treatment-card'>
                        <h2>{$row['nome']}</h2>
                        <p>{$row['duracao']}</p>
                        <p>R$ {$row['preco']}</p>
                        <button onclick='openPopup({$row['id']}, \"{$row['nome']}\", \"{$row['duracao']}\", \"{$row['preco']}\")'>Editar</button>
                    </div>";
                }
            } else {
                echo "<p>Nenhum serviço encontrado</p>";
            }
            ?>
        </div>
    </div>

    <div class="popup" id="editPopup">
        <div class="popup-content">
            <div class="close" onclick="closePopup()">×</div>
            <h2>Editar Serviço</h2>
            <form method="POST" id="editForm">
                <input type="hidden" name="id" id="serviceId">
                <input type="text" name="nome" id="serviceName" placeholder="Nome do Serviço" required>
                <input type="text" name="duracao" id="serviceDuration" placeholder="Duração" required>
                <input type="text" name="preco" id="servicePrice" placeholder="Preço" required>
                <button type="submit" name="update_service">Salvar</button>
            </form>
        </div>
    </div>

    <script>
        function openPopup(id, nome, duracao, preco) {
            document.getElementById("serviceId").value = id;
            document.getElementById("serviceName").value = nome;
            document.getElementById("serviceDuration").value = duracao;
            document.getElementById("servicePrice").value = preco;
            document.getElementById("editPopup").style.display = "flex";
        }

        function closePopup() {
            document.getElementById("editPopup").style.display = "none";
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>
