<!DOCTYPE html>
<html>
<head>
    <title>Agendamento</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        /* Estilos gerais */
        body {  
            font-family: Arial, sans-serif;
            color: #000;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: transparent;
        }

        .container {
            width: 600px;
            padding: 20px;
            text-align: center;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .header a {
            text-decoration: none;
            color: #000;
            font-size: 14px;
            display: flex;
            align-items: center;
        }

        .header a i {
            margin-right: 5px;
        }

        .title {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .subtitle {
            font-size: 12px;
            margin-bottom: 20px;
            color: #555;
        }

        /* Estilos para o calendário */
        .calendar {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
        }

        .calendar label {
            margin-right: 10px;
        }

        #date-picker {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
            transition: border 0.3s ease;
            width: 100%;
            max-width: 200px;
        }

        #date-picker:focus {
            outline: none;
            border-color: #4CAF50;
        }

        /* Estilos para seleção de horário */
        .time-selection {
            margin-bottom: 20px;
        }

        .time-selection p {
            font-size: 14px;
            margin-bottom: 10px;
        }

        .time-buttons {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }

        .time-buttons button {
            margin: 5px;
            padding: 10px 20px;
            border: 1px solid #000;
            background-color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .time-buttons button.selected {
            background-color: #000;
            color: #fff;
        }

        .time-buttons button.disabled {
            background-color: #f0f0f0;
            cursor: not-allowed;
            color: #999;
        }

        .time-buttons button:hover:not(.disabled):not(.selected) {
            background-color: #000;
            color: #fff;
        }

        /* Estilos para informações do agendamento */
        .info {
            margin-bottom: 20px;
        }

        .info p {
            font-size: 14px;
            margin: 5px 0;
        }

        /* Estilos para o botão do formulário */
        .footer {
            text-align: center;
        }

        .footer button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #000;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        .footer button:hover {
            background-color: #333;
        }

        .welcome-message {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #4CAF50;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            text-align: center;
            z-index: 1000;
            display: none; /* Escondido por padrão */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="Menu Precos.php"><i class="fas fa-chevron-left"></i> Voltar</a>
        </div>
        <div class="title">Selecione uma data e horário</div>
        <div class="subtitle">Horário Padrão de Brasília (BRT)</div>

        <!-- Campo de Data -->
        <div class="calendar">
            <label for="date-picker">Escolha uma data:</label>
            <input type="date" id="date-picker" name="serviceDate" required>
        </div>
        
        <!-- Seleção de Horário -->
        <div class="time-selection">
            <p>Apenas algumas sessões ainda estão disponíveis para agendamento. Os agendamentos serão encerrados 1 minuto antes da sessão iniciar.</p>
            <div class="time-buttons" id="time-buttons">
                <button class="disabled">14:30</button>
                <button onclick="selectTime(this)" class="selected">15:00</button>
                <button onclick="selectTime(this)">15:30</button>
                <button onclick="selectTime(this)">16:00</button>
                <button onclick="selectTime(this)">16:30</button>
            </div>
        </div>

        <!-- Informações de Agendamento -->
        <div class="info">
            <p>Informações do agendamento</p>
            <p id="service-name">Corte com Escova</p>
            <p id="service-duration-price">1 - 2h • R$ 90</p> <!-- Exibição do preço do serviço -->
            <p id="service-address">Rua Sarandi, 22, Vila Bela</p>
        </div>

        <!-- Contêiner para Serviços Adicionais -->
        <div id="additional-services-info"></div>

        <!-- Formulário para enviar os dados do agendamento -->
        <form id="agendamento-form" onsubmit="return submitForm(event);">
            <input type="hidden" name="serviceName" id="service-name-input" value="Corte com Escova">
            <input type="hidden" name="serviceDurationPrice" id="service-duration-price-input" value="1 - 2h • R$ 90"> <!-- Campo oculto para o preço -->
            <input type="hidden" name="serviceDate" id="service-date-input"> <!-- Campo oculto para a data -->
            <input type="hidden" name="serviceTime" id="selected-time-input">
            <input type="hidden" name="serviceAddress" id="service-address-input" value="Rua Sarandi, 22, Vila Bela">
            <input type="hidden" name="userName" id="user-name-input" value="<?php session_start(); echo $_SESSION['user_name']; ?>">
            <input type="hidden" name="additionalServices" id="additional-services-input">

            <div class="footer">
                <button type="submit">Próximo</button>
            </div>
        </form>
    </div>

    <!-- JavaScript para seleção de horário e envio do formulário -->
    <script>
        function selectTime(button) {
            const selectedTimeButton = document.querySelector(".time-buttons button.selected");
            if (selectedTimeButton) selectedTimeButton.classList.remove("selected");
            button.classList.add("selected");

            const selectedTime = button.textContent;
            document.getElementById("selected-time-input").value = selectedTime;
        }

        document.getElementById('date-picker').addEventListener('change', function() {
            const selectedDate = this.value;
            document.getElementById("service-date-input").value = selectedDate; // Armazena a data selecionada
            checkAvailability(selectedDate); // Verifica a disponibilidade de horários ao mudar a data
        });

        document.addEventListener("DOMContentLoaded", () => {
            const serviceName = localStorage.getItem('serviceName');
            const serviceDurationPrice = localStorage.getItem('serviceDurationPrice');
            const additionalServices = JSON.parse(localStorage.getItem('additionalServices')) || [];

            if (serviceName && serviceDurationPrice) {
                document.getElementById('service-name').textContent = serviceName; // Atualiza o nome do serviço
                document.getElementById('service-duration-price').textContent = serviceDurationPrice; // Atualiza o preço exibido
                document.getElementById('service-name-input').value = serviceName; // Define no campo oculto
                document.getElementById('service-duration-price-input').value = serviceDurationPrice; // Define no campo oculto
            }

            // Adiciona os serviços adicionais à informação do agendamento em linhas separadas
            const additionalInfoContainer = document.getElementById('additional-services-info');
            additionalServices.forEach(service => {
                const serviceParagraph = document.createElement('p');
                serviceParagraph.textContent = service.name; // Nome do serviço adicional
                additionalInfoContainer.appendChild(serviceParagraph);
            });

            // Armazena serviços adicionais no campo oculto
            document.getElementById('additional-services-input').value = JSON.stringify(additionalServices);
        });

        function submitForm(event) {
            event.preventDefault(); // Impede o envio do formulário padrão

            const formData = new FormData(document.getElementById('agendamento-form'));

            // Faz a requisição para salvar o agendamento
            fetch('salvar_agendamento.php', {
                method: 'POST',
                body: formData // formData deve incluir todos os dados do formulário
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message); // Exibe a mensagem de sucesso
                    setTimeout(() => {
                        window.location.href = 'index.php'; // Redireciona para index.php após 2 segundos
                    }, 2000); // Tempo em milissegundos
                } else {
                    alert('Erro: ' + data.message); // Exibe mensagem de erro
                }
            })
            .catch(error => {
                console.error('Erro ao enviar o formulário:', error);
                alert('Ocorreu um erro ao agendar. Tente novamente.');
            });

            return false; // Impede o envio do formulário
        }

        // Verifica a disponibilidade de horários com base na data selecionada
        function checkAvailability(selectedDate) {
            // Aqui você pode fazer uma requisição para verificar a disponibilidade
            // e atualizar os botões de horário com base na resposta do servidor.
        }
    </script>
</body>
</html>
