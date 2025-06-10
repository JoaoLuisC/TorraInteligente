@extends('master')

@section('title', 'Meus Torradores')

@section('MainContent')
<div class="container-fluid">
    <div class="row">
        @forelse($torradores as $torrador)
            <div class="col-md-4">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">{{ $torrador->nome }}</h3>
                    </div>
                    <div class="card-body">
                        <p><strong>Código de Conexão:</strong> {{ $torrador->codigo_conexao }}</p>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    Nenhum torrador cadastrado ainda.
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection