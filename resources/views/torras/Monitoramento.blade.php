@extends('master')

@section('title', 'Monitoramento em Tempo Real')
@section('breadcrumb-title', 'Monitoramento de Torra')

@section('MainContent')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Seleção de Torra -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-coffee me-2"></i>
                        Selecionar Torra para Monitoramento
                    </h4>
                </div>
                <div class="card-body">
                    @if($torras->count() > 0)
                        <div class="row">
                            <div class="col-md-4">
                                <label for="select-torra" class="form-label">Escolha uma torra não avaliada:</label>
                                <select class="form-control" id="select-torra">
                                    <option value="">Selecione uma torra...</option>
                                    @foreach($torras as $torra)
                                        <option value="{{ $torra->id }}"
                                                data-nome="{{ $torra->nome }}"
                                                data-variedade="{{ $torra->variedade }}"
                                                data-fermentacao="{{ $torra->fermentacao }}"
                                                data-finalidade="{{ $torra->finalidade }}"
                                                data-densidade="{{ $torra->densidade }}"
                                                data-observacoes="{{ $torra->observacoes }}">
                                            {{ $torra->nome }} - {{ $torra->variedade }} ({{ date('d/m/Y H:i', strtotime($torra->criado_em)) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="select-torrador" class="form-label">Selecione o torrador:</label>
                                <select class="form-control" id="select-torrador">
                                    <option value="">Selecione um torrador...</option>
                                    @foreach($torradores as $torrador)
                                        <option value="{{ $torrador->id }}"
                                                data-nome="{{ $torrador->nome }}"
                                                data-codigo="{{ $torrador->codigo_conexao }}">
                                            {{ $torrador->nome }} ({{ $torrador->codigo_conexao }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="button" class="btn btn-success btn-lg" id="btn-iniciar-monitoramento" disabled>
                                    <i class="fas fa-play me-2"></i>
                                    Iniciar Monitoramento
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <h5><i class="fas fa-info-circle me-2"></i>Nenhuma torra disponível</h5>
                            <p class="mb-2">Você não possui torras configuradas e não avaliadas para monitoramento.</p>
                            <a href="{{ route('torras.iniciar') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>
                                Configurar Nova Torra
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Informações da Torra Selecionada -->
    <div class="row mb-4" id="info-torra-selecionada" style="display: none;">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-info-circle me-2"></i>Informações da Torra</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2"><strong>Nome:</strong> <span id="display-nome"></span></div>
                        <div class="col-md-2"><strong>Variedade:</strong> <span id="display-variedade"></span></div>
                        <div class="col-md-2"><strong>Fermentação:</strong> <span id="display-fermentacao"></span></div>
                        <div class="col-md-2"><strong>Finalidade:</strong> <span id="display-finalidade"></span></div>
                        <div class="col-md-2"><strong>Densidade:</strong> <span id="display-densidade"></span></div>
                        <div class="col-md-2">
                            <strong>Status:</strong>
                            <span id="status-torra" class="badge bg-warning">Aguardando</span>
                        </div>
                    </div>
                    <div class="row mt-2" id="row-observacoes" style="display: none;">
                        <div class="col-12">
                            <strong>Observações:</strong> <span id="display-observacoes"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dados em Tempo Real -->
    <div class="row mb-4" id="dados-tempo-real">
        <div class="col-md-3">
            <div class="card text-center opacity-50" id="card-temperatura">
                <div class="card-body">
                    <h4 class="text-muted" id="temperatura-atual">--°C</h4>
                    <p class="mb-0 text-muted">Temperatura Atual</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center opacity-50" id="card-tempo">
                <div class="card-body">
                    <h4 class="text-muted" id="tempo-torra">--:--</h4>
                    <p class="mb-0 text-muted">Tempo de Torra</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center opacity-50" id="card-velocidade">
                <div class="card-body">
                    <h4 class="text-muted" id="velocidade">--°C/min</h4>
                    <p class="mb-0 text-muted">Velocidade</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center opacity-50" id="card-umidade">
                <div class="card-body">
                    <h4 class="text-muted" id="umidade">--%</h4>
                    <p class="mb-0 text-muted">Umidade</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfico -->
    <div class="row mb-4" id="grafico-container">
        <div class="col-12">
            <div class="card opacity-50" id="card-grafico">
                <div class="card-header">
                    <h5 class="text-muted"><i class="fas fa-chart-line me-2"></i>Curva de Temperatura em Tempo Real</h5>
                </div>
                <div class="card-body">
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-chart-line fa-3x mb-3"></i>
                        <p>Aguardando início do monitoramento...</p>
                    </div>
                    <canvas id="temperatura-chart" height="100" style="display: none;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Controles de Torra -->
    <div class="row mb-4" id="controles-torra">
        <div class="col-12">
            <div class="card opacity-50" id="card-controles">
                <div class="card-header">
                    <h5 class="text-muted"><i class="fas fa-sliders-h me-2"></i>Controles de Torra</h5>
                </div>
                <div class="card-body text-center">
                    <button class="btn btn-outline-secondary mx-1" id="btn-aquecimento" disabled>
                        <i class="fas fa-thermometer-half me-1"></i>Aquecimento
                    </button>
                    <button class="btn btn-outline-secondary mx-1" id="btn-first-crack" disabled>
                        <i class="fas fa-fire me-1"></i>First Crack
                    </button>
                    <button class="btn btn-outline-secondary mx-1" id="btn-second-crack" disabled>
                        <i class="fas fa-fire me-1"></i>Second Crack
                    </button>
                    <button class="btn btn-outline-secondary mx-1" id="btn-pausar" disabled>
                        <i class="fas fa-pause me-1"></i>Pausar
                    </button>
                    <button class="btn btn-outline-secondary mx-1" id="btn-retomar" disabled style="display: none;">
                        <i class="fas fa-play me-1"></i>Retomar
                    </button>
                    <button id="btn-finalizar" class="btn btn-outline-secondary mx-1" disabled>
                        <i class="fas fa-stop me-1"></i>Finalizar Torra
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Logs de Eventos -->
    <div class="row" id="logs-container">
        <div class="col-12">
            <div class="card opacity-50" id="card-logs">
                <div class="card-header">
                    <h5 class="text-muted"><i class="fas fa-list me-2"></i>Log de Eventos</h5>
                </div>
                <div class="card-body">
                    <div id="logs-eventos" style="max-height: 200px; overflow-y: auto;">
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-clock me-2"></i>
                            Aguardando eventos...
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Variáveis globais
    let torraChart;
    let torraInterval;
    let torraStartTime;
    let temperaturaAtual = 25;
    let torraIniciada = false;
    let torraPausada = false;
    let torraFinalizada = false;
    let torraSelecionada = null;

    // Aguardar carregamento completo
    window.addEventListener('load', function() {
        console.log('Página carregada completamente');
        inicializarSistema();
    });

    function inicializarSistema() {
        console.log('Inicializando sistema...');

        const selectTorra = document.getElementById('select-torra');
        const selectTorrador = document.getElementById('select-torrador');
        const btnIniciarMonitoramento = document.getElementById('btn-iniciar-monitoramento');

        console.log('Elementos encontrados:', {
            selectTorra: selectTorra,
            selectTorrador: selectTorrador,
            btnIniciarMonitoramento: btnIniciarMonitoramento
        });

        if (!selectTorra || !selectTorrador || !btnIniciarMonitoramento) {
            console.error('Elementos críticos não encontrados!');
            return;
        }

        // Função para verificar se ambos os campos estão preenchidos
        function verificarSelecoes() {
            const torraSelecionada = selectTorra.value && selectTorra.value !== '';
            const torradorSelecionado = selectTorrador.value && selectTorrador.value !== '';

            if (torraSelecionada && torradorSelecionado) {
                btnIniciarMonitoramento.disabled = false;
                btnIniciarMonitoramento.style.opacity = '1';
            } else {
                btnIniciarMonitoramento.disabled = true;
                btnIniciarMonitoramento.style.opacity = '0.6';
            }
        }

        // Event listeners para ambos os selects
        selectTorra.onchange = function() {
            console.log('Mudança detectada no select torra:', this.value);

            if (this.value && this.value !== '') {
                // Pegar dados da opção selecionada
                const opcaoSelecionada = this.options[this.selectedIndex];
                console.log('Opção selecionada:', opcaoSelecionada);

                // Mostrar informações da torra
                preencherInformacoesTorra(opcaoSelecionada);

                torraSelecionada = this.value;
                console.log('Torra selecionada salva:', torraSelecionada);
            }

            verificarSelecoes();
        };

        selectTorrador.onchange = function() {
            console.log('Mudança detectada no select torrador:', this.value);
            verificarSelecoes();
        };

        // Inicializar estado do botão
        verificarSelecoes();
                torraSelecionada = null;

                // Esconder informações
                const infoDiv = document.getElementById('info-torra-selecionada');
                if (infoDiv) infoDiv.style.display = 'none';
            }
        };

        // Event listener para botão de iniciar
        btnIniciarMonitoramento.onclick = function(e) {
            e.preventDefault();
            console.log('=== CLIQUE NO BOTÃO DETECTADO ===');
            console.log('torraSelecionada:', torraSelecionada);

            // Para teste, vamos simular uma torra selecionada
            if (!torraSelecionada) {
                console.log('Simulando torra selecionada para teste...');
                torraSelecionada = 'teste';
            }

            console.log('Iniciando processo de monitoramento...');

            // Desabilitar botão e mostrar loading
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Iniciando...';
            this.style.pointerEvents = 'none';

            // Executar após pequeno delay para dar feedback visual
            setTimeout(function() {
                iniciarMonitoramento();
            }, 200);

            return false;
        };

        console.log('Event listeners configurados com sucesso!');
        configurarControles();
    }

    function preencherInformacoesTorra(opcao) {
        console.log('Preenchendo informações da torra...');

        const elementos = {
            'display-nome': opcao.dataset.nome,
            'display-variedade': opcao.dataset.variedade,
            'display-fermentacao': opcao.dataset.fermentacao,
            'display-finalidade': opcao.dataset.finalidade,
            'display-densidade': (opcao.dataset.densidade || '0') + ' g/cm³'
        };

        for (const [id, valor] of Object.entries(elementos)) {
            const elemento = document.getElementById(id);
            if (elemento && valor) {
                elemento.textContent = valor;
            }
        }

        // Tratar observações
        const observacoes = opcao.dataset.observacoes;
        const displayObservacoes = document.getElementById('display-observacoes');
        const rowObservacoes = document.getElementById('row-observacoes');

        if (observacoes && observacoes !== 'null' && observacoes.trim() !== '') {
            if (displayObservacoes) displayObservacoes.textContent = observacoes;
            if (rowObservacoes) rowObservacoes.style.display = 'block';
        } else {
            if (rowObservacoes) rowObservacoes.style.display = 'none';
        }

        // Mostrar div de informações
        const infoDiv = document.getElementById('info-torra-selecionada');
        if (infoDiv) {
            infoDiv.style.display = 'block';
        }
    }

    function iniciarMonitoramento() {
        console.log('=== INICIANDO MONITORAMENTO ===');

        try {
            // 1. Mostrar seções de monitoramento
            console.log('Passo 1: Mostrando seções...');
            mostrarSecoesMonitoramento();

            // 2. Inicializar gráfico
            console.log('Passo 2: Inicializando gráfico...');
            inicializarGrafico();

            // 3. Habilitar controles
            console.log('Passo 3: Habilitando controles...');
            habilitarControles();

            // 4. Atualizar status
            console.log('Passo 4: Atualizando status...');
            atualizarStatusTorra('Monitorando', 'bg-success');

            // 5. Adicionar log inicial
            console.log('Passo 5: Adicionando log...');
            adicionarLogEvento('Monitoramento iniciado', 'info');

            // 6. Iniciar simulação
            console.log('Passo 6: Iniciando simulação...');
            iniciarSimulacao();

            // 7. Atualizar botão
            console.log('Passo 7: Atualizando botão...');
            const btn = document.getElementById('btn-iniciar-monitoramento');
            if (btn) {
                btn.innerHTML = '<i class="fas fa-check me-2"></i>Monitoramento Ativo';
                btn.className = 'btn btn-success btn-lg';
                btn.style.pointerEvents = 'auto';
            }

            console.log('=== MONITORAMENTO INICIADO COM SUCESSO ===');

        } catch (error) {
            console.error('Erro ao iniciar monitoramento:', error);

            // Restaurar botão em caso de erro
            const btn = document.getElementById('btn-iniciar-monitoramento');
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-play me-2"></i>Iniciar Monitoramento';
                btn.style.pointerEvents = 'auto';
            }
        }
    }

    function mostrarSecoesMonitoramento() {
        console.log('Ativando seções de monitoramento...');

        // Ativar cards de dados em tempo real
        const cardsDados = [
            { card: 'card-temperatura', value: 'temperatura-atual', color: 'text-primary' },
            { card: 'card-tempo', value: 'tempo-torra', color: 'text-info' },
            { card: 'card-velocidade', value: 'velocidade', color: 'text-success' },
            { card: 'card-umidade', value: 'umidade', color: 'text-warning' }
        ];

        cardsDados.forEach(item => {
            const cardElement = document.getElementById(item.card);
            const valueElement = document.getElementById(item.value);

            if (cardElement) {
                cardElement.className = cardElement.className.replace('opacity-50', '');
                cardElement.querySelector('p').className = cardElement.querySelector('p').className.replace('text-muted', '');
            }

            if (valueElement) {
                valueElement.className = item.color;
            }
        });

        // Ativar gráfico
        const cardGrafico = document.getElementById('card-grafico');
        if (cardGrafico) {
            cardGrafico.className = cardGrafico.className.replace('opacity-50', '');
            cardGrafico.querySelector('h5').className = '';

            // Esconder mensagem de aguardando e mostrar canvas
            const aguardandoMsg = cardGrafico.querySelector('.text-center.text-muted');
            const canvas = document.getElementById('temperatura-chart');

            if (aguardandoMsg) aguardandoMsg.style.display = 'none';
            if (canvas) canvas.style.display = 'block';
        }

        // Ativar controles
        const cardControles = document.getElementById('card-controles');
        if (cardControles) {
            cardControles.className = cardControles.className.replace('opacity-50', '');
            cardControles.querySelector('h5').className = '';
        }

        // Ativar logs
        const cardLogs = document.getElementById('card-logs');
        if (cardLogs) {
            cardLogs.className = cardLogs.className.replace('opacity-50', '');
            cardLogs.querySelector('h5').className = '';

            // Limpar mensagem de aguardando
            const logsContainer = document.getElementById('logs-eventos');
            if (logsContainer) {
                logsContainer.innerHTML = '';
            }
        }

        console.log('Seções ativadas com sucesso!');
    }

    function atualizarStatusTorra(texto, classe) {
        const statusElement = document.getElementById('status-torra');
        if (statusElement) {
            statusElement.textContent = texto;
            statusElement.className = `badge ${classe}`;
        }
    }    function inicializarGrafico() {
        console.log('Criando gráfico...');
        const ctx = document.getElementById('temperatura-chart');
        if (!ctx) {
            console.error('Canvas do gráfico não encontrado!');
            return;
        }

        torraChart = new Chart(ctx.getContext('2d'), {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Temperatura (°C)',
                    data: [],
                    borderColor: 'rgb(255, 99, 132)',
                    backgroundColor: 'rgba(255, 99, 132, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: false,
                        min: 20,
                        max: 250,
                        title: {
                            display: true,
                            text: 'Temperatura (°C)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Tempo'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                animation: {
                    duration: 750
                }
            }
        });
        console.log('Gráfico criado com sucesso!');
    }

    function habilitarControles() {
        const controles = [
            { id: 'btn-aquecimento', classe: 'btn btn-outline-primary mx-1' },
            { id: 'btn-first-crack', classe: 'btn btn-outline-info mx-1' },
            { id: 'btn-second-crack', classe: 'btn btn-outline-warning mx-1' },
            { id: 'btn-pausar', classe: 'btn btn-outline-secondary mx-1' },
            { id: 'btn-finalizar', classe: 'btn btn-danger mx-1' }
        ];

        controles.forEach(controle => {
            const elemento = document.getElementById(controle.id);
            if (elemento) {
                elemento.disabled = false;
                elemento.className = controle.classe;
                console.log(`Controle ${controle.id} habilitado`);
            }
        });
    }

    function adicionarLogEvento(mensagem, tipo = 'info') {
        console.log('Adicionando log:', mensagem);
        const agora = new Date();
        const timestamp = agora.toLocaleTimeString();

        const logContainer = document.getElementById('logs-eventos');
        if (!logContainer) {
            console.warn('Container de logs não encontrado');
            return;
        }

        const logEntry = document.createElement('div');
        logEntry.className = `alert alert-${tipo} alert-sm mb-1 py-1`;
        logEntry.innerHTML = `<small><strong>${timestamp}</strong> - ${mensagem}</small>`;

        logContainer.appendChild(logEntry);
        logContainer.scrollTop = logContainer.scrollHeight;
    }

    function iniciarSimulacao() {
        console.log('Iniciando simulação de dados...');

        if (!torraStartTime) {
            torraStartTime = Date.now();
        }

        // Definir valores iniciais
        temperaturaAtual = 25;

        // Atualizar displays iniciais
        atualizarDisplay('temperatura-atual', '25°C');
        atualizarDisplay('tempo-torra', '00:00');
        atualizarDisplay('velocidade', '0.0°C/min');
        atualizarDisplay('umidade', '20%');

        torraIniciada = true;

        torraInterval = setInterval(() => {
            if (torraFinalizada) {
                console.log('Simulação parada - torra finalizada');
                return;
            }

            // Simular aquecimento gradual realístico
            if (temperaturaAtual < 150) {
                temperaturaAtual += Math.random() * 2 + 0.5; // Aquecimento inicial
            } else if (temperaturaAtual < 200) {
                temperaturaAtual += Math.random() * 1.5 + 0.3; // Aquecimento médio
            } else {
                temperaturaAtual += Math.random() * 0.8 - 0.2; // Oscilação no final
            }

            const tempoDecorrido = Math.floor((Date.now() - torraStartTime) / 1000);
            const minutos = Math.floor(tempoDecorrido / 60);
            const segundos = tempoDecorrido % 60;
            const tempoFormatado = minutos.toString().padStart(2, '0') + ':' + segundos.toString().padStart(2, '0');

            // Calcular velocidade (taxa de aquecimento)
            const velocidade = temperaturaAtual < 150 ? '3.2°C/min' :
                              temperaturaAtual < 200 ? '2.1°C/min' : '0.8°C/min';

            // Simular umidade
            const umidade = Math.max(5, 20 - (temperaturaAtual - 25) * 0.1 + Math.random() * 2);

            // Atualizar displays
            atualizarDisplay('temperatura-atual', Math.round(temperaturaAtual) + '°C');
            atualizarDisplay('tempo-torra', tempoFormatado);
            atualizarDisplay('velocidade', velocidade);
            atualizarDisplay('umidade', Math.round(umidade) + '%');

            // Atualizar gráfico a cada 3 segundos
            if (tempoDecorrido % 3 === 0 && torraChart) {
                torraChart.data.labels.push(tempoFormatado);
                torraChart.data.datasets[0].data.push(Math.round(temperaturaAtual));

                // Manter apenas os últimos 30 pontos para performance
                if (torraChart.data.labels.length > 30) {
                    torraChart.data.labels.shift();
                    torraChart.data.datasets[0].data.shift();
                }

                torraChart.update('none'); // Animação mais suave
            }
        }, 1000);

        console.log('Simulação iniciada!');
    }

    function atualizarDisplay(id, valor) {
        const elemento = document.getElementById(id);
        if (elemento) {
            elemento.textContent = valor;
        }
    }

    function configurarControles() {
        console.log('Configurando controles...');

        // Aquecimento
        const btnAquecimento = document.getElementById('btn-aquecimento');
        if (btnAquecimento) {
            btnAquecimento.onclick = function() {
                adicionarLogEvento('Fase de aquecimento marcada', 'primary');
                this.disabled = true;
                this.className = 'btn btn-primary mx-1';
            };
        }

        // First Crack
        const btnFirstCrack = document.getElementById('btn-first-crack');
        if (btnFirstCrack) {
            btnFirstCrack.onclick = function() {
                adicionarLogEvento('First Crack identificado', 'info');
                this.disabled = true;
                this.className = 'btn btn-info mx-1';
            };
        }

        // Second Crack
        const btnSecondCrack = document.getElementById('btn-second-crack');
        if (btnSecondCrack) {
            btnSecondCrack.onclick = function() {
                adicionarLogEvento('Second Crack identificado', 'warning');
                this.disabled = true;
                this.className = 'btn btn-warning mx-1';
            };
        }

        // Pausar
        const btnPausar = document.getElementById('btn-pausar');
        const btnRetomar = document.getElementById('btn-retomar');
        if (btnPausar) {
            btnPausar.onclick = function() {
                if (!torraPausada) {
                    clearInterval(torraInterval);
                    torraPausada = true;
                    this.style.display = 'none';
                    if (btnRetomar) {
                        btnRetomar.style.display = 'inline-block';
                        btnRetomar.disabled = false;
                    }
                    adicionarLogEvento('Torra pausada', 'secondary');
                    atualizarStatusTorra('Pausada', 'bg-secondary');
                }
            };
        }

        // Retomar
        if (btnRetomar) {
            btnRetomar.onclick = function() {
                if (torraPausada) {
                    iniciarSimulacao();
                    torraPausada = false;
                    this.style.display = 'none';
                    if (btnPausar) btnPausar.style.display = 'inline-block';
                    adicionarLogEvento('Torra retomada', 'success');
                    atualizarStatusTorra('Monitorando', 'bg-success');
                }
            };
        }

        // Finalizar
        const btnFinalizar = document.getElementById('btn-finalizar');
        if (btnFinalizar) {
            btnFinalizar.onclick = function() {
                if (confirm('Tem certeza que deseja finalizar esta torra?')) {
                    clearInterval(torraInterval);
                    torraFinalizada = true;

                    // Desabilitar todos os controles
                    const todosControles = ['btn-aquecimento', 'btn-first-crack', 'btn-second-crack', 'btn-pausar', 'btn-retomar', 'btn-finalizar'];
                    todosControles.forEach(id => {
                        const elemento = document.getElementById(id);
                        if (elemento) elemento.disabled = true;
                    });

                    atualizarStatusTorra('Finalizada', 'bg-danger');
                    adicionarLogEvento('Torra finalizada com sucesso', 'danger');

                    // Exibir opções pós-torra
                    setTimeout(() => {
                        if (confirm('Torra finalizada! Deseja ir para a lista de torras?')) {
                            window.location.href = "{{ route('torras.index') }}";
                        }
                    }, 1000);
                }
            };
        }

        console.log('Controles configurados!');
    }

    function adicionarLogEvento(mensagem, tipo = 'info') {
        console.log('Adicionando log:', mensagem, tipo);
        const agora = new Date();
        const timestamp = agora.toLocaleTimeString();

        const logContainer = document.getElementById('logs-eventos');
        if (logContainer) {
            const logEntry = document.createElement('div');
            logEntry.className = `alert alert-${tipo} alert-sm mb-1 py-1`;
            logEntry.innerHTML = `<small><strong>${timestamp}</strong> - ${mensagem}</small>`;

            logContainer.appendChild(logEntry);
            logContainer.scrollTop = logContainer.scrollHeight;
        }
    }

    function atualizarStatusTorra(status, classe) {
        console.log('Atualizando status para:', status);
        const statusElement = document.getElementById('status-torra');
        if (statusElement) {
            statusElement.textContent = status;
            statusElement.className = 'badge ' + classe;
        }
    }

    // Aguardar carregamento completo da página
    window.onload = function() {
        console.log('=== PÁGINA CARREGADA ===');
        console.log('DOM State:', document.readyState);

        // Aguardar um pouco mais para garantir que tudo foi renderizado
        setTimeout(function() {
            console.log('Executando inicialização...');
            inicializarSistema();
        }, 100);
    };

    // Backup para caso window.onload não funcione
    document.addEventListener('DOMContentLoaded', function() {
        console.log('=== DOM CONTENT LOADED ===');

        // Só executar se window.onload ainda não executou
        setTimeout(function() {
            if (!document.getElementById('btn-iniciar-monitoramento').onclick) {
                console.log('Fallback: executando inicialização...');
                inicializarSistema();
            }
        }, 200);
    });
</script>
@endsection
