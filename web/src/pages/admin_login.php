<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espaço VIP Luciana - Admin Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap">
    <link rel="stylesheet" href="../Styles/Style_index.css">
    <style>
        .welcome-message {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            text-align: center;
            z-index: 1000;
            display: none; /* Escondido por padrão */
        }

        .welcome-message.success {
            background-color: #4CAF50; /* Verde */
            color: #fff;
        }

        .welcome-message.error {
            background-color: #f44336; /* Vermelho */
            color: #fff;
        }
    </style>
    <script>
        function toggleForm() {
            var loginForm = document.getElementById('login-form');
            var signupForm = document.getElementById('signup-form');
            loginForm.classList.toggle('hidden');
            signupForm.classList.toggle('hidden');
        }

        function checkUser() {
            fetch('check_login_status.php')
                .then(response => response.json())
                .then(data => {
                    if (data.loggedIn) {
                        document.getElementById('loginButton').innerHTML = '<a href="#" onclick="logout()"><i class="fas fa-user"></i>Sair</a>';
                        
                        // Exibe a mensagem de boas-vindas apenas uma vez após o login
                        if (sessionStorage.getItem('showWelcome') === 'true') {
                            showMessage('Bem-vindo de volta!', 'success');
                            sessionStorage.removeItem('showWelcome'); // Remove a flag para evitar exibição contínua
                        }
                    }
                });
        }

        function onLoginSuccess() {
            sessionStorage.setItem('showWelcome', 'true'); // Marca para exibir a mensagem de boas-vindas no próximo carregamento
            checkUser();
        }

        function logout() {
            fetch('logout.php')
                .then(response => {
                    if (response.ok) {
                        sessionStorage.removeItem('showWelcome'); // Remove a flag ao fazer logout
                        showMessage('Você saiu com sucesso!', 'error'); // Exibe a mensagem de logout
                        setTimeout(() => {
                            window.location.href = 'index.php'; // Redireciona após exibir a mensagem
                        }, 3000); // Aguarda 3 segundos antes de redirecionar
                    } else {
                        alert("Erro ao fazer logout.");
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert("Erro ao fazer logout. Tente novamente.");
                });
        }

        function showMessage(message, type = 'success') {
            const messageElement = document.createElement('div');
            messageElement.classList.add('welcome-message', type); // Adiciona a classe correspondente ao tipo
            messageElement.textContent = message;
            document.body.appendChild(messageElement);
            messageElement.style.display = 'block';

            setTimeout(() => {
                messageElement.style.display = 'none';
                document.body.removeChild(messageElement);
            }, 3000); // Mensagem desaparece após 3 segundos
        }

        function closePopup() {
            document.getElementById('loginPopup').style.display = 'none';
        }

        // Chama a função checkUser ao carregar a página
        window.onload = checkUser;
    </script>
</head>
<body>
    <div class="login" id="loginButton">
        <a href="#" onclick="logout()"><i class="fas fa-user"></i>Sair</a>
    </div>
    <div class="container">
        <h1 class="welcome">Welcome to Admin Panel</h1>
        <h2 class="title">Espaço VIP Luciana</h2>
        <p class="subtitle">Área Administrativa</p>
        <div class="button">
            <a href="menu_editar.php">Editar Menu</a>
        </div>
    </div>
    
    <!-- Popup de Login -->
    <div id="loginPopup" class="popup">
        <span class="close-btn" onclick="closePopup()">&times;</span>
        <div class="container-popup">
            <div class="left">
                <h1>Olá, <span>bem-vindo à área admin!</span></h1>
                <form id="login-form" action="admin_login.php" method="post" onsubmit="onLoginSuccess(); return true;">
                    <input type="email" id="username" name="email" placeholder="Endereço de email" required>
                    <input type="password" id="password" name="senha" placeholder="Senha" required>
                    <label>
                        <input type="checkbox"> Lembrar-me
                    </label>
                    <a class="forgot-password" href="#">Esqueceu a senha?</a>
                    <button class="login-btn" type="submit">Entrar</button>
                    <button class="signup-btn" onclick="toggleForm()" type="button">Registrar-se</button>
                </form>
                <form id="signup-form" class="hidden" action="cadastro.php" method="post">
                    <input type="text" id="reg_username" name="nome" placeholder="Nome completo" required>
                    <input type="email" id="reg_email" name="email" placeholder="Endereço de email" required>
                    <input type="password" id="reg_password" name="senha" placeholder="Senha" required>
                    <button class="register-button" type="submit">Registrar-se</button>
                    <button class="signup-btn" onclick="toggleForm()" type="button">Voltar ao Login</button>
                </form>
                <div class="social">
                    <span>SIGA-ME</span>
                    <a href="https://www.instagram.com/lucianaboava?igsh=dzU2d3E3azRrMG9s" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://www.instagram.com/lucianaboava?igsh=dzU2d3E3azRrMG9s" target="_blank"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            <div class="right">
                <img src="https://i.pinimg.com/474x/b7/8f/06/b78f06d25cc162433f320d9c30d47ed1.jpg" alt="Formas geométricas abstratas com cores vibrantes" width="500" height="500">
            </div>
        </div>
    </div>
</body>
</html>
