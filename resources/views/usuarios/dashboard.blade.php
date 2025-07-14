{{-- resources/views/home.blade.php --}}
@extends('master')

@section('title', 'Página Inicial')

@section('MainContent')
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-8">
            <div class="card-body text-center">
                <h1 class="display-4">Bem-vindo!</h1>
            </div>
        </div>
    </div>

    <!-- Avaliações de Torras -->
    <div class="card mb-4 mt-5">
        <div class="card-header border-0">
            <h3 class="card-title">Avaliações de Torras</h3>
            <div class="card-tools">
                <a href="#" class="btn btn-tool btn-sm"> <i class="bi bi-download"></i> </a>
                <a href="#" class="btn btn-tool btn-sm"> <i class="bi bi-list"></i> </a>
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>Nome da Torra</th>
                        <th>Grão do Café</th>
                        <th>Nota do Café</th>
                        <th>Mais</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <img
                                src="../../../public/images/icons/3-graos-de-cafe.png"
                                alt="Torra Média"
                                class="rounded-circle img-size-32 me-2"
                            />
                            Torra Média
                        </td>
                        <td>Bourbon Amarelo</td>
                        <td>
                            <small class="text-success me-1">
                                <i class="bi bi-arrow-up"></i>
                                8.7
                            </small>
                        </td>
                        <td>
                            <a href="#" class="text-secondary"> <i class="bi bi-search"></i> </a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <img
                                src="../../"
                                alt="Torra Clara"
                                class="rounded-circle img-size-32 me-2"
                            />
                            Torra Clara
                        </td>
                        <td>Catuaí Vermelho</td>
                        <td>
                            <small class="text-info me-1">
                                <i class="bi bi-arrow-down"></i>
                                7.9
                            </small>
                        </td>
                        <td>
                            <a href="#" class="text-secondary"> <i class="bi bi-search"></i> </a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <img
                                src="../../"
                                alt="Torra Escura"
                                class="rounded-circle img-size-32 me-2"
                            />
                            Torra Escura
                        </td>
                        <td>Mundo Novo</td>
                        <td>
                            <small class="text-danger me-1">
                                <i class="bi bi-arrow-down"></i>
                                6.5
                            </small>
                        </td>
                        <td>
                            <a href="#" class="text-secondary"> <i class="bi bi-search"></i> </a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <img
                                src="../../"
                                alt="Torra Especial"
                                class="rounded-circle img-size-32 me-2"
                            />
                            Torra Especial
                            <span class="badge text-bg-danger">NOVA</span>
                        </td>
                        <td>Arara</td>
                        <td>
                            <small class="text-success me-1">
                                <i class="bi bi-arrow-up"></i>
                                9.2
                            </small>
                        </td>
                        <td>
                            <a href="#" class="text-secondary"> <i class="bi bi-search"></i> </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Últimas Torras -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Últimas Torras</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-fire"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Torra Média</span>
                                    <span class="info-box-number">2024-06-01</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-fire"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Torra Clara</span>
                                    <span class="info-box-number">2024-05-28</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-fire"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Torra Escura</span>
                                    <span class="info-box-number">2024-05-25</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Últimos Torradores -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Últimos Torradores</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-user"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">João Silva</span>
                                    <span class="info-box-number">joao@email.com</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-user"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Maria Souza</span>
                                    <span class="info-box-number">maria@email.com</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-user"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Carlos Lima</span>
                                    <span class="info-box-number">carlos@email.com</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
@endsection
