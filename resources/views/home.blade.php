@extends('layouts.landing')

@section('title', 'Michelangelo')

@section('MainContent')
    <header class="hero-header d-flex align-items-center justify-content-center">
        <div class="container text-center hero-content">
            <h1 class="display-4 fw-bold text-white shadow-text">Paixão pelo Café, Compromisso com a Qualidade</h1>
            <p class="lead text-white shadow-text">Unimos tecnologia e amor para transformar cada torra em uma experiência única</p>
        </div>
    </header>

    <section id="produtos" class="container my-5">
        <h2 class="text-center mb-5">Nossos Produtos</h2>
        <div class="row align-items-stretch mb-5">
            <div class="col-md-6 d-flex">
                <div class="bg-white rounded shadow p-4 w-100 h-100 d-flex flex-column justify-content-center">
                    <h4 class="mb-3"><i class="fas fa-microchip text-primary"></i> Hardware de Ponta</h4>
                    <p>
                        Desenvolvemos um produto pensado tanto para os amantes de café que buscam uma imersão completa no processo de torra,
                        quanto para os profissionais que desejam elevar a qualidade de seus grãos.
                        O Projeto <b>Michelangelo</b> combina tecnologia de ponta e sensores avançados,
                        oferecendo precisão e controle total no monitoramento e análise do café durante a torra.
                    </p>
                </div>
            </div>
            <div class="col-md-3 d-flex">
                <div class="bg-white rounded shadow w-100 h-100 d-flex align-items-center justify-content-center">
                    <img src="{{ asset('images/modelo3dFrente.jpeg') }}" alt="Futuro 1" class="img-fluid rounded shadow" style="max-height: 250px; object-fit: contain;">
                </div>
            </div>
            <div class="col-md-3 d-flex">
                <div class="bg-white rounded shadow w-100 h-100 d-flex align-items-center justify-content-center">
                    <img src="{{ asset('images/modelo3dLado.jpeg') }}" alt="Futuro 2" class="img-fluid rounded shadow" style="max-height: 250px; object-fit: contain;">
                </div>
            </div>
        </div>

        <div class="row align-items-stretch">
            <div class="col-md-4 d-flex">
                <div class="bg-white rounded shadow w-100 h-100 d-flex align-items-center justify-content-center">
                    <img src="{{ asset('images/logoMichelangelo.png') }}" alt="Logo" class="img-fluid rounded shadow" style="max-width: 250px; max-height: 180px; object-fit: contain;">
                </div>
            </div>
            <div class="col-md-8 d-flex">
                <div class="bg-white rounded shadow p-4 w-100 h-100 d-flex flex-column justify-content-center">
                    <h4 class="mb-3"><i class="fas fa-seedling text-success"></i> Nosso Futuro</h4>
                    <p>
                        Atualmente, nosso sistema utiliza o software Artisan para a leitura e monitoramento de gráficos durante a torra.
                        No entanto, nosso objetivo é alcançar independência tecnológica, integrando todo o processo de monitoramento diretamente em nossa plataforma,
                        com um ambiente intuitivo, amigável e profissional.
                        <br>
                        Além disso, visamos expandir nossa solução para além do universo do café,
                        aplicando nossa tecnologia e sensores em diversos processos do setor agropecuário, promovendo inovação, precisão e sustentabilidade no campo.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section id="servicos" class="container my-5">
        <h2 class="text-center mb-5 titulo_section">Nossos Serviços</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card card-primary h-100 shadow">
                    <div class="card-header text-center bg-primary text-white">
                        <i class="fas fa-lightbulb fa-2x mb-2"></i>
                        <h4 class="card-title mb-0">Tecnologia e Inovação</h4>
                    </div>
                    <div class="card-body">
                        <p>Integramos sensores inteligentes e soluções eletrônicas ao processo de torra para garantir precisão, automação e controle em tempo real da produção de café.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-success h-100 shadow">
                    <div class="card-header text-center bg-success text-white">
                        <i class="fas fa-globe fa-2x mb-2"></i>
                        <h4 class="card-title mb-0">Plataforma Web</h4>
                    </div>
                    <div class="card-body">
                        <p>Oferecemos uma interface amigável para análise dos dados de torra, controle de qualidade e registro completo das etapas, tudo em um só lugar.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-warning h-100 shadow">
                    <div class="card-header text-center bg-warning text-dark">
                        <i class="fas fa-coffee fa-2x mb-2"></i>
                        <h4 class="card-title mb-0">Qualidade e Análise Sensorial</h4>
                    </div>
                    <div class="card-body">
                        <p>Ferramentas para registrar e avaliar sensorialmente cada café, promovendo consistência, padronização e evolução contínua na produção.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<!-- FontAwesome para ícones -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
@endpush
