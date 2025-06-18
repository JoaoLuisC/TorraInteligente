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
