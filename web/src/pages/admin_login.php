<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espaço VIP Luciana - Admin Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap">
    <link rel="stylesheet" href="../Styles/Style_index.css">
    <script>
        function toggleForm() {
            var loginForm = document.getElementById('login-form');
            var signupForm = document.getElementById('signup-form');
            loginForm.classList.toggle('hidden');
            signupForm.classList.toggle('hidden');
        }

        function checkLoginStatus() {
            fetch('check_login_status.php')
                .then(response => response.json())
                .then(data => {
                    if (data.loggedIn) {
                        window.location.href = 'Menu Precos.php';
                    } else {
                        document.getElementById('loginPopup').style.display = 'block';
                        showMessage('Você precisa estar logado para agendar um horário.');
                    }
                });
        }

        function checkUser() {
            fetch('check_login_status.php')
                .then(response => response.json())
                .then(data => {
                    if (data.loggedIn) {
                        document.getElementById('loginButton').innerHTML = '<a href="#" onclick="logout()"><i class="fas fa-user"></i>Sair</a>';
                        showMessage('Bem-vindo de volta!');
                    }
                });
        }

        function logout() {
            fetch('logout.php')
                .then(response => response.json())
                .then(data => {
                    if (data.loggedOut) {
                        document.getElementById('loginButton').innerHTML = '<a href="#" onclick="document.getElementById(\'loginPopup\').style.display=\'block\'"><i class="fas fa-user"></i>Login</a>';
                        showMessage('Você saiu do sistema com sucesso.');
                    }
                });
        }

        function closePopup() {
            document.getElementById('loginPopup').style.display = 'none';
        }

        window.onload = checkUser;
    </script>
</head>
<body>
    <div class="login" id="loginButton">
        <a href="#" onclick="document.getElementById('loginPopup').style.display='block'"><i class="fas fa-user"></i>Login Admin</a>
    </div>
    <div class="container">
        <h1 class="welcome">Welcome to Admin Panel</h1>
        <h2 class="title">Espaço VIP Luciana</h2>
        <p class="subtitle">Área Administrativa</p>
        <div class="button">
            <a href="#" onclick="checkLoginStatus()">Agende um horário</a>
        </div>
    </div>
    <div class="whatsapp">
        <a href="https://wa.me/5542999821726" target="_blank"><i class="fab fa-whatsapp"></i>Vamos conversar</a>
    </div>
    
    <!-- Popup de Login -->
    <div id="loginPopup" class="popup">
        <span class="close-btn" onclick="closePopup()">&times;</span> <!-- Botão de fechar -->
        <div class="container-popup">
            <div class="left">
                <h1>Olá, <span>bem-vindo à área admin!</span></h1>
                <form id="login-form" action="admin_login.php" method="post" onsubmit="showMessage('Login realizado com sucesso!'); return true;">
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
