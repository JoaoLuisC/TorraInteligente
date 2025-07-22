@extends('master')

@section('title', 'Dashboard - Produtor')
@section('breadcrumb-title', 'Dashboard')

@section('MainContent')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            @if(empty($dbTables) || !$dbTables['torras'])
                <!-- Mensagem quando as tabelas não existem -->
                <div class="alert alert-info text-center">
                    <h4><i class="fas fa-database"></i> Sistema Inicializando</h4>
                    <p>O sistema está sendo configurado. Algumas funcionalidades podem estar temporariamente indisponíveis.</p>
                    <p>Se você é um administrador, verifique se todas as tabelas do banco de dados foram criadas corretamente.</p>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-coffee"></i> Bem-vindo ao Torra Inteligente
                        </h3>
                    </div>
                    <div class="card-body">
                        <p>Seja bem-vindo ao sistema de análise de torras de café! Assim que o sistema estiver completamente configurado, você poderá:</p>
                        <ul>
                            <li><i class="fas fa-plus"></i> Criar e gerenciar suas torras</li>
                            <li><i class="fas fa-paper-plane"></i> Solicitar análises sensoriais</li>
                            <li><i class="fas fa-chart-line"></i> Visualizar resultados e estatísticas</li>
                            <li><i class="fas fa-star"></i> Acompanhar a qualidade do seu café</li>
                        </ul>
                    </div>
                </div>
            @else
                <!-- Dashboard normal quando as tabelas existem -->
                <div class="row">
                    <!-- Estatísticas -->
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ $estatisticas['total_torras'] ?? 0 }}</h3>
                                <p>Total de Torras</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-coffee"></i>
                            </div>
                            <a href="{{ route('torras.index') }}" class="small-box-footer">
                                Ver todas <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $estatisticas['torras_avaliadas'] ?? 0 }}</h3>
                                <p>Torras Avaliadas</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <a href="{{ route('torras.index') }}?filtro_avaliacao=avaliadas" class="small-box-footer">
                                Ver detalhes <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ $estatisticas['torras_aguardando'] ?? 0 }}</h3>
                                <p>Aguardando Avaliação</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-hourglass-half"></i>
                            </div>
                            <a href="{{ route('torras.index') }}?filtro_avaliacao=aguardando_avaliacao" class="small-box-footer">
                                Ver status <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ $estatisticas['torras_nao_avaliadas'] ?? 0 }}</h3>
                                <p>Não Avaliadas</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <a href="{{ route('torras.index') }}?filtro_avaliacao=nao_avaliadas" class="small-box-footer">
                                Solicitar análise <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Últimas Torras -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-history"></i> Últimas Torras
                                </h3>
                                <div class="card-tools">
                                    <a href="{{ route('torras.iniciar') }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-plus"></i> Nova Torra
                                    </a>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                @if($ultimasTorras && $ultimasTorras->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Nome</th>
                                                    <th>Variedade</th>
                                                    <th>Status</th>
                                                    <th>Criada</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($ultimasTorras as $torra)
                                                <tr>
                                                    <td>{{ $torra->nome }}</td>
                                                    <td>{{ $torra->variedade }}</td>
                                                    <td>
                                                        @if($torra->status === 'avaliada')
                                                            <span class="badge badge-success">Avaliada</span>
                                                        @elseif($torra->status === 'aguardando_avaliacao')
                                                            <span class="badge badge-warning">Aguardando</span>
                                                        @else
                                                            <span class="badge badge-secondary">Não Avaliada</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($torra->criado_em)->format('d/m/Y') }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-coffee fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Nenhuma torra criada ainda.</p>
                                        <a href="{{ route('torras.iniciar') }}" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Criar Primeira Torra
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Melhores Torras -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-trophy"></i> Melhores Torras
                                </h3>
                            </div>
                            <div class="card-body p-0">
                                @if($melhoresTorras && $melhoresTorras->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Nome</th>
                                                    <th>Nota</th>
                                                    <th>Variedade</th>
                                                    <th>Avaliada</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($melhoresTorras as $torra)
                                                <tr>
                                                    <td>{{ $torra->torra_nome }}</td>
                                                    <td>
                                                        <span class="badge badge-success">
                                                            {{ number_format($torra->nota_final, 1) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $torra->variedade }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($torra->data_avaliacao)->format('d/m/Y') }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-star fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Nenhuma torra avaliada ainda.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if($distribuicaoVariedades && $distribuicaoVariedades->count() > 0)
                <!-- Distribuição de Variedades -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-chart-pie"></i> Distribuição de Variedades
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach($distribuicaoVariedades as $variedade)
                                    <div class="col-md-4">
                                        <div class="info-box">
                                            <span class="info-box-icon bg-primary">
                                                <i class="fas fa-seedling"></i>
                                            </span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">{{ $variedade->variedade }}</span>
                                                <span class="info-box-number">{{ $variedade->total }} torras</span>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
