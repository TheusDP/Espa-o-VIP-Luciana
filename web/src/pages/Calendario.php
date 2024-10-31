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
            background-color: transparent; /* Fundo transparente */
        }

        .container {
            width: 600px;
            padding: 20px;
            text-align: center;
            background-color: #fff; /* Fundo branco para o container */
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
            <div class="time-buttons">
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
            <p id="service-duration-price">1 - 2h • R$ 90</p>
            <p id="service-address">Rua Sarandi, 22, Vila Bela</p>
        </div>

        <!-- Formulário para enviar os dados do agendamento -->
        <form id="agendamento-form" action="salvar_agendamento.php" method="POST">
            <input type="hidden" name="serviceName" id="service-name-input" value="Corte com Escova">
            <input type="hidden" name="serviceDurationPrice" id="service-duration-price-input" value="1 - 2h • R$ 90">
            <input type="hidden" name="serviceDate" id="service-date-input"> <!-- Campo oculto para a data -->
            <input type="hidden" name="serviceTime" id="selected-time-input">
            <input type="hidden" name="serviceAddress" id="service-address-input" value="Rua Sarandi, 22, Vila Bela">
            <div class="footer">
                <button type="submit">Próximo</button>
            </div>
        </form>
    </div>

    <!-- JavaScript para seleção de horário -->
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
        });

        document.addEventListener("DOMContentLoaded", () => {
            const serviceName = localStorage.getItem('serviceName');
            const serviceDurationPrice = localStorage.getItem('serviceDurationPrice');
            if (serviceName && serviceDurationPrice) {
                document.getElementById('service-name').textContent = serviceName;
                document.getElementById('service-duration-price').textContent = serviceDurationPrice;
                document.getElementById('service-name-input').value = serviceName;
                document.getElementById('service-duration-price-input').value = serviceDurationPrice;
            }
        });
    </script>
</body>
</html>
