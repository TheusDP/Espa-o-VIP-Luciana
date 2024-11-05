<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Menu de Procedimentos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap"> <!-- Importação da fonte -->
    <link rel="stylesheet" href="../Styles/Style_Menu Precos.css">
</head>
<body>
    <?php include 'get_services.php'; ?>

    <div class="container">
        <div class="header">
            <a href="index.php" class="back"><i class="fas fa-arrow-left"></i> Voltar</a>
        </div>
        <h1 class="title">Menu de Procedimentos</h1>
        <div class="treatment-grid">
            <?php foreach ($servicos as $servico): ?>
                <div class="treatment-card">
                    <h2><?php echo htmlspecialchars($servico['nome']); ?></h2>
                    <p><?php echo htmlspecialchars($servico['duracao']); ?></p>
                    <p><?php echo htmlspecialchars($servico['preco']); ?></p>
                    <button onclick="openPopup('<?php echo addslashes($servico['nome']); ?>', '<?php echo addslashes($servico['duracao']); ?>', ' <?php echo addslashes($servico['preco']); ?>')">Agendar agora</button>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Botão para ver agendamentos -->
        <button class="view-agendamentos" onclick="openAgendamentosPopup()">Ver Agendamentos</button>
    </div>
    
    <div class="whatsapp">
        <a href="https://wa.me/5542999821726" target="_blank">
            <i class="fab fa-whatsapp"></i>
            <span>Vamos conversar</span>
        </a>
    </div>

    <!-- Popup de agendamentos -->
    <div class="popup" id="agendamentos-popup">
        <div class="popup-content">
            <div class="header">
                <h1>Seus Agendamentos</h1>
                <span class="close" onclick="closeAgendamentosPopup()">&times;</span>
            </div>
            <div id="agendamentos-content">
                <!-- Os agendamentos serão preenchidos aqui via JavaScript -->
            </div>
        </div>
    </div>

    <!-- Popup para agendar -->
    <div class="popup" id="popup">
        <div class="popup-content">
            <div class="header">
                <h1>Seu agendamento</h1>
                <span class="close" onclick="closePopup()">&times;</span>
            </div>
            <div class="service-box" id="service-box">
                <p id="service-name"></p>
                <p>Rua Sarandi, 22, Vila Bela</p>
                <p id="service-duration-price"></p>
            </div>
            <div id="additional-services"></div>
            <p>Gostaria de adicionar outro serviço a este agendamento?</p>
            <div class="add-service" onclick="openSecondPopup()">
                <i class="fas fa-plus"></i>
                <span>Adicionar serviço</span>
            </div>
            <div class="footer">
                <button onclick="redirectToSchedule()">Selecionar data e horário</button>
            </div>
        </div>
    </div>

    <!-- Second Popup -->
    <div class="second-popup" id="second-popup">
        <div class="second-popup-content">
            <div class="header">
                <h2>Selecione outro serviço</h2>
                <span class="close" onclick="closeSecondPopup()">&times;</span>
            </div>
            <div class="content">
                <?php foreach ($servicos as $servico): ?>
                    <div class="service-item">
                        <label>
                            <input type="radio" name="service" value="<?php echo htmlspecialchars($servico['nome']); ?>">
                            <?php echo htmlspecialchars($servico['nome']); ?>
                            <span class="price"><?php echo htmlspecialchars($servico['duracao']); ?> • <?php echo htmlspecialchars($servico['preco']); ?></span>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="footer">
                <button class="back" onclick="closeSecondPopup()">Voltar</button>
                <button class="next" onclick="addServiceToPopup()">Próximo</button>
            </div>
        </div>
    </div>

    <script>
        function openPopup(serviceName, serviceDuration, servicePrice) {
            const mainServiceName = document.getElementById('service-name').innerText;
            if (mainServiceName && mainServiceName === serviceName) {
                alert('Este serviço já foi selecionado como serviço principal.');
                return;
            }
            document.getElementById('service-name').innerText = serviceName;
            document.getElementById('service-duration-price').innerText = serviceDuration + ' • ' + servicePrice;
            document.getElementById('popup').style.display = 'flex';
        }

        function closePopup() {
            document.getElementById('popup').style.display = 'none';
        }

        function openSecondPopup() {
            document.getElementById('second-popup').style.display = 'flex';
        }

        function closeSecondPopup() {
            document.getElementById('second-popup').style.display = 'none';
        }

        function addServiceToPopup() {
            const selectedService = document.querySelector('input[name="service"]:checked');
            if (selectedService) {
                const serviceName = selectedService.value;
                const serviceDetails = selectedService.nextElementSibling.innerText;

                const existingServices = document.querySelectorAll('#additional-services .service-box p:first-child');
                for (let service of existingServices) {
                    if (service.innerText === serviceName) {
                        alert('Este serviço já foi adicionado.');
                        return;
                    }
                }

                const mainServiceName = document.getElementById('service-name').innerText;
                if (mainServiceName === serviceName) {
                    alert('Este serviço já foi selecionado como serviço principal.');
                    return;
                }

                const serviceBox = document.createElement('div');
                serviceBox.className = 'service-box';
                serviceBox.innerHTML = `
                    <p>${serviceName}</p>
                    <p>Rua Sarandi, 22, Vila Bela</p>
                    <p>${serviceDetails}</p>
                    <button class="delete-btn" onclick="removeService(this)"><i class="fas fa-trash"></i></button>
                `;

                document.getElementById('additional-services').appendChild(serviceBox);
                closeSecondPopup();
            }
        }

        function removeService(button) {
            const serviceBox = button.parentElement;
            serviceBox.remove();
        }

        function redirectToSchedule() {
            const serviceName = document.getElementById('service-name').innerText;
            const serviceDurationPrice = document.getElementById('service-duration-price').innerText;
            const additionalServices = [];
            document.querySelectorAll('#additional-services .service-box').forEach(serviceBox => {
                const service = {
                    name: serviceBox.querySelector('p:first-child').innerText,
                    details: serviceBox.querySelector('p:nth-child(3)').innerText
                };
                additionalServices.push(service);
            });

            localStorage.setItem('serviceName', serviceName);
            localStorage.setItem('serviceDurationPrice', serviceDurationPrice);
            localStorage.setItem('additionalServices', JSON.stringify(additionalServices));

            window.location.href = 'Calendario.php';
        }

        function openAgendamentosPopup() {
            fetch('get_agendamentos.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const agendamentos = data.agendamentos.map(agendamento => `
                            <div class="service-box">
                                <p>Serviço: ${agendamento.service_name}</p>
                                <p>Data: ${agendamento.service_date}</p>
                                <p>Horário: ${agendamento.service_time}</p>
                            </div>
                        `).join('');
                        document.getElementById('agendamentos-content').innerHTML = agendamentos;
                    } else {
                        document.getElementById('agendamentos-content').innerHTML = '<p>Nenhum agendamento encontrado.</p>';
                    }
                })
                .catch(error => {
                    console.error('Erro ao buscar agendamentos:', error);
                    document.getElementById('agendamentos-content').innerHTML = '<p>Erro ao carregar agendamentos.</p>';
                });
            document.getElementById('agendamentos-popup').style.display = 'flex';
        }

        function closeAgendamentosPopup() {
            document.getElementById('agendamentos-popup').style.display = 'none';
        }
    </script>
</body>
</html>
