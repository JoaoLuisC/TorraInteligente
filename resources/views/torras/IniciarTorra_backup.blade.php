@extends('master')

@section('title', 'Configurar Torra')
@section('breadcrumb-title', 'Configurar Nova Torra')

@section('MainContent')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-fire me-2"></i>
                        Configurar Nova Torra
                    </h4>
                </div>
                <div class="card-body">
                    <form id="nova-torra-form" action="{{ route('torras.store') }}" method="POST" autocomplete="off">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="nome" class="form-label">Nome da Torra *</label>
                                    <input type="text" class="form-control @error('nome') is-invalid @enderror"
                                           id="nome" name="nome" value="{{ old('nome') }}" required>
                                    @error('nome')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="densidade" class="form-label">Densidade (g/cm³) *</label>
                                    <input type="number" step="0.01" min="0"
                                           class="form-control @error('densidade') is-invalid @enderror"
                                           id="densidade" name="densidade" value="{{ old('densidade') }}" required>
                                    @error('densidade')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="variedade" class="form-label">Variedade *</label>
                                    <select class="form-control @error('variedade') is-invalid @enderror"
                                            id="variedade" name="variedade" required>
                                        <option value="">Selecione a variedade</option>
                                        <option value="Arábico" {{ old('variedade') == 'Arábico' ? 'selected' : '' }}>Arábico</option>
                                        <option value="Bourbon" {{ old('variedade') == 'Bourbon' ? 'selected' : '' }}>Bourbon</option>
                                        <option value="Catuaí" {{ old('variedade') == 'Catuaí' ? 'selected' : '' }}>Catuaí</option>
                                        <option value="Mundo Novo" {{ old('variedade') == 'Mundo Novo' ? 'selected' : '' }}>Mundo Novo</option>
                                        <option value="Typica" {{ old('variedade') == 'Typica' ? 'selected' : '' }}>Typica</option>
                                        <option value="Geisha" {{ old('variedade') == 'Geisha' ? 'selected' : '' }}>Geisha</option>
                                        <option value="Caturra" {{ old('variedade') == 'Caturra' ? 'selected' : '' }}>Caturra</option>
                                    </select>
                                    @error('variedade')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="fermentacao" class="form-label">Fermentação *</label>
                                    <select class="form-control @error('fermentacao') is-invalid @enderror"
                                            id="fermentacao" name="fermentacao" required>
                                        <option value="">Selecione a fermentação</option>
                                        <option value="Natural" {{ old('fermentacao') == 'Natural' ? 'selected' : '' }}>Natural</option>
                                        <option value="Fermentado" {{ old('fermentacao') == 'Fermentado' ? 'selected' : '' }}>Fermentado</option>
                                        <option value="CD" {{ old('fermentacao') == 'CD' ? 'selected' : '' }}>CD</option>
                                    </select>
                                    @error('fermentacao')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="finalidade" class="form-label">Finalidade *</label>
                                    <select class="form-control @error('finalidade') is-invalid @enderror"
                                            id="finalidade" name="finalidade" required>
                                        <option value="">Selecione a finalidade</option>
                                        <option value="Espresso" {{ old('finalidade') == 'Espresso' ? 'selected' : '' }}>Espresso</option>
                                        <option value="Filtro" {{ old('finalidade') == 'Filtro' ? 'selected' : '' }}>Filtro</option>
                                        <option value="Amostra" {{ old('finalidade') == 'Amostra' ? 'selected' : '' }}>Amostra</option>
                                    </select>
                                    @error('finalidade')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label for="observacoes" class="form-label">Observações</label>
                                    <textarea class="form-control @error('observacoes') is-invalid @enderror"
                                              id="observacoes" name="observacoes" rows="3" maxlength="500"
                                              placeholder="Informações adicionais sobre esta torra...">{{ old('observacoes') }}</textarea>
                                    @error('observacoes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Máximo 500 caracteres</div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer bg-white border-0 px-0">
                            <button type="submit" class="btn btn-primary btn-lg" id="btn-configurar-torra">
                                <i class="fas fa-save me-2"></i>
                                Configurar Torra
                            </button>
                            <a href="{{ route('torras.index') }}" class="btn btn-outline-secondary btn-lg ajax-link">
                                <i class="fas fa-arrow-left me-2"></i>
                                Voltar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Card informativo -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-info">
                <div class="card-body">
                    <h5 class="card-title text-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Próximo Passo
                    </h5>
                    <p class="card-text">
                        Após configurar sua torra, você será redirecionado para a tela de 
                        <strong>Monitoramento</strong> onde poderá iniciar o processo de torra em tempo real.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('nova-torra-form');
    const submitBtn = document.getElementById('btn-configurar-torra');
    
    if (form && submitBtn) {
        form.addEventListener('submit', function() {
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Configurando...';
            submitBtn.disabled = true;
        });
    }
});
</script>
@endsection
                            <div class="row">
                                <div class="col-md-3"><strong>Nome:</strong> <span id="display-nome"></span></div>
                                <div class="col-md-3"><strong>Variedade:</strong> <span id="display-variedade"></span></div>
                                <div class="col-md-3"><strong>Fermentação:</strong> <span id="display-fermentacao"></span></div>
                                <div class="col-md-3"><strong>Finalidade:</strong> <span id="display-finalidade"></span></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dados em Tempo Real -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h4 class="text-primary" id="temperatura-atual">25°C</h4>
                                <p class="mb-0">Temperatura Atual</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h4 class="text-info" id="tempo-torra">00:00</h4>
                                <p class="mb-0">Tempo de Torra</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h4 class="text-success" id="velocidade">0°C/min</h4>
                                <p class="mb-0">Velocidade</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h4 class="text-warning" id="densidade-display">0.00 g/cm³</h4>
                                <p class="mb-0">Densidade</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gráfico -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fas fa-chart-line me-2"></i>Curva de Temperatura</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="temperatura-chart" height="100"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Controles -->
                <div class="text-center">
                    <button class="btn btn-outline-primary mx-1" id="btn-1" disabled>
                        <i class="fas fa-thermometer-half me-1"></i>Aquecimento
                    </button>
                    <button class="btn btn-outline-info mx-1" id="btn-2" disabled>
                        <i class="fas fa-fire me-1"></i>First Crack
                    </button>
                    <button class="btn btn-outline-warning mx-1" id="btn-3" disabled>
                        <i class="fas fa-fire me-1"></i>Second Crack
                    </button>
                    <button class="btn btn-outline-secondary mx-1" id="btn-4" disabled>
                        <i class="fas fa-pause me-1"></i>Pausar
                    </button>
                    <button class="btn btn-outline-success mx-1" id="btn-5" disabled>
                        <i class="fas fa-play me-1"></i>Retomar
                    </button>
                    <button id="btn-finalizar" class="btn btn-danger mx-1" type="button" style="display: none;">
                        <i class="fas fa-stop me-1"></i>Finalizar Torra
                    </button>
                    <button id="btn-salvar" class="btn btn-success mx-1" style="display:none;" type="button">
                        <i class="fas fa-save me-1"></i>Salvar Torra
                    </button>
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
    let torraChart;
    let torraInterval;
    let torraStartTime;
    let temperaturaAtual = 25;
    let torraData = [];
    let torraIniciada = false;

    document.addEventListener('DOMContentLoaded', function () {
        const headingForm = document.getElementById('headingForm');
        const collapseForm = document.getElementById('collapseForm');
        const iconForm = document.getElementById('iconForm');
        const headingMonitoramento = document.getElementById('headingMonitoramento');
        const collapseMonitoramento = document.getElementById('collapseMonitoramento');
        const iconMonitoramento = document.getElementById('iconMonitoramento');
        const form = document.getElementById('nova-torra-form');
        const btnFinalizar = document.getElementById('btn-finalizar');
        const btnSalvar = document.getElementById('btn-salvar');

        // Inicialmente desabilita o monitoramento
        headingMonitoramento.style.pointerEvents = 'none';
        headingMonitoramento.style.opacity = '0.5';

        // Toggle formulário
        headingForm.addEventListener('click', function() {
            if (collapseForm.classList.contains('show')) {
                collapseForm.classList.remove('show');
                iconForm.classList.remove('bi-chevron-down');
                iconForm.classList.add('bi-chevron-right');
            } else {
                collapseForm.classList.add('show');
                iconForm.classList.remove('bi-chevron-right');
                iconForm.classList.add('bi-chevron-down');
            }
        });

        // Toggle monitoramento
        headingMonitoramento.addEventListener('click', function() {
            if (headingMonitoramento.style.pointerEvents === 'none') return;
            if (collapseMonitoramento.classList.contains('show')) {
                collapseMonitoramento.classList.remove('show');
                iconMonitoramento.classList.remove('bi-chevron-down');
                iconMonitoramento.classList.add('bi-chevron-right');
            } else {
                collapseMonitoramento.classList.add('show');
                iconMonitoramento.classList.remove('bi-chevron-right');
                iconMonitoramento.classList.add('bi-chevron-down');
            }
        });

        // Envio do formulário
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(form);
            const btnIniciar = document.getElementById('btn-iniciar-torra');

            btnIniciar.disabled = true;
            btnIniciar.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Iniciando...';

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Atualizar informações da torra
                    document.getElementById('display-nome').textContent = formData.get('nome');
                    document.getElementById('display-variedade').textContent = formData.get('variedade');
                    document.getElementById('display-fermentacao').textContent = formData.get('fermentacao');
                    document.getElementById('display-finalidade').textContent = formData.get('finalidade');
                    document.getElementById('densidade-display').textContent = formData.get('densidade') + ' g/cm³';

                    // Mostrar info da torra
                    document.getElementById('info-torra').style.display = 'block';

                    // Minimizar formulário
                    collapseForm.classList.remove('show');
                    iconForm.classList.remove('bi-chevron-down');
                    iconForm.classList.add('bi-chevron-right');

                    // Habilitar e expandir monitoramento
                    headingMonitoramento.style.pointerEvents = 'auto';
                    headingMonitoramento.style.opacity = '1';
                    collapseMonitoramento.classList.add('show');
                    iconMonitoramento.classList.remove('bi-chevron-right');
                    iconMonitoramento.classList.add('bi-chevron-down');

                    // Mostrar status e controles
                    document.getElementById('status-torra').style.display = 'inline-block';
                    document.getElementById('btn-finalizar').style.display = 'inline-block';

                    // Habilitar botões de controle
                    ['btn-1', 'btn-2', 'btn-3', 'btn-4', 'btn-5'].forEach(id => {
                        document.getElementById(id).disabled = false;
                    });

                    // Iniciar simulação
                    iniciarSimulacao();
                } else {
                    alert('Erro: ' + (data.message || 'Erro desconhecido'));
                    btnIniciar.disabled = false;
                    btnIniciar.innerHTML = '<i class="fas fa-play me-2"></i>Iniciar Torra';
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao iniciar torra');
                btnIniciar.disabled = false;
                btnIniciar.innerHTML = '<i class="fas fa-play me-2"></i>Iniciar Torra';
            });
        });

        // Finalizar torra
        btnFinalizar.addEventListener('click', function(e) {
            e.preventDefault();
            clearInterval(torraInterval);
            btnSalvar.style.display = 'inline-block';
            btnFinalizar.disabled = true;
            document.getElementById('status-torra').textContent = 'Finalizada';
            document.getElementById('status-torra').className = 'badge bg-danger ms-2';
        });

        // Salvar torra
        btnSalvar.addEventListener('click', function(e) {
            e.preventDefault();

            // Aqui você pode enviar os dados da torra para o servidor
            alert('Torra salva com sucesso!');

            // Redirecionar para a lista de torras
            window.location.href = "{{ route('torras.index') }}";
        });

        // Inicializar gráfico
        inicializarGrafico();
    });

    function inicializarGrafico() {
        const ctx = document.getElementById('temperatura-chart').getContext('2d');
        torraChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Temperatura (°C)',
                    data: [],
                    borderColor: 'rgb(255, 99, 132)',
                    backgroundColor: 'rgba(255, 99, 132, 0.1)',
                    borderWidth: 2,
                    fill: true
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
                }
            }
        });
    }

    function iniciarSimulacao() {
        torraStartTime = Date.now();
        torraIniciada = true;

        torraInterval = setInterval(() => {
            // Simular aquecimento gradual
            if (temperaturaAtual < 200) {
                temperaturaAtual += Math.random() * 3 + 1; // 1-4°C por segundo
            } else {
                temperaturaAtual += Math.random() * 1 - 0.5; // Oscilação próximo ao máximo
            }

            const tempoDecorrido = Math.floor((Date.now() - torraStartTime) / 1000);
            const minutos = Math.floor(tempoDecorrido / 60);
            const segundos = tempoDecorrido % 60;
            const tempoFormatado = minutos.toString().padStart(2, '0') + ':' + segundos.toString().padStart(2, '0');

            // Atualizar displays
            document.getElementById('temperatura-atual').textContent = Math.round(temperaturaAtual) + '°C';
            document.getElementById('tempo-torra').textContent = tempoFormatado;
            document.getElementById('velocidade').textContent = '2.5°C/min';

            // Atualizar gráfico a cada 5 segundos
            if (tempoDecorrido % 5 === 0) {
                torraChart.data.labels.push(tempoFormatado);
                torraChart.data.datasets[0].data.push(Math.round(temperaturaAtual));

                // Manter apenas os últimos 20 pontos
                if (torraChart.data.labels.length > 20) {
                    torraChart.data.labels.shift();
                    torraChart.data.datasets[0].data.shift();
                }

                torraChart.update();
            }
        }, 1000);
    }
</script>
@endsection
