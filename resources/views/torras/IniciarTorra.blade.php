@extends('master')

@section('title', 'Iniciar Torra')

@section('MainContent')
<div class="accordion" id="torraAccordion">
    <!-- Formulário -->
    <div class="card">
        <div class="card-header" id="headingForm" style="cursor:pointer;">
            <h3 class="mb-0 d-flex justify-content-between align-items-center">
                <span>Iniciar Nova Torra</span>
                <span id="iconForm" class="bi bi-chevron-down"></span>
            </h3>
        </div>
        <div id="collapseForm" class="collapse show" aria-labelledby="headingForm" data-parent="#torraAccordion">
            <div class="card-body">
                <form id="nova-torra-form" autocomplete="off">
                    <div class="form-group">
                        <label for="nome">Nome da Torra</label>
                        <input type="text" class="form-control" id="nome" name="nome" required>
                    </div>
                    <div class="form-group">
                        <label for="variedade">Variedade</label>
                        <select class="form-control" id="variedade" name="variedade" required>
                            <option value="">Selecione</option>
                            <option value="Arábico">Arábico</option>
                            <option value="Bourbon">Bourbon</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="densidade">Densidade</label>
                        <input type="number" step="0.01" class="form-control" id="densidade" name="densidade" required>
                    </div>
                    <div class="form-group">
                        <label for="fermentacao">Fermentação</label>
                        <select class="form-control" id="fermentacao" name="fermentacao" required>
                            <option value="">Selecione</option>
                            <option value="Natural">Natural</option>
                            <option value="Fermentado">Fermentado</option>
                            <option value="CD">CD</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="finalidade">Finalidade</label>
                        <select class="form-control" id="finalidade" name="finalidade" required>
                            <option value="">Selecione</option>
                            <option value="Espresso">Espresso</option>
                            <option value="Filtro">Filtro</option>
                            <option value="Amostra">Amostra</option>
                        </select>
                    </div>
                    <div class="card-footer bg-white border-0 px-0">
                        <button type="submit" class="btn btn-primary">Iniciar Torra</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Monitoramento -->
    <div class="card mt-2">
        <div class="card-header" id="headingMonitoramento" style="cursor:pointer; opacity:0.5;">
            <h3 class="mb-0 d-flex justify-content-between align-items-center">
                <span>Monitoramento em tempo real</span>
                <span id="iconMonitoramento" class="bi bi-chevron-right"></span>
            </h3>
        </div>
        <div id="collapseMonitoramento" class="collapse" aria-labelledby="headingMonitoramento" data-parent="#torraAccordion">
            <div class="card-body">
                <div class="d-flex">
                    <p class="d-flex flex-column">
                        <span class="fw-bold fs-5">0</span> <span>Temperatura Atual</span>
                    </p>
                    <p class="ms-auto d-flex flex-column text-end">
                        <span class="text-success"> <i class="bi bi-arrow-up"></i> 0% </span>
                        <span class="text-secondary">Tempo de Torra</span>
                    </p>
                </div>
                <div class="position-relative mb-4">
                    <div id="visitors-chart" style="height:250px;"></div>
                </div>
                <div class="d-flex flex-row justify-content-end">
                    <span class="me-2">
                        <i class="bi bi-square-fill text-primary"></i> Atual
                    </span>
                </div>
                <div class="mb-3 text-center">
                    <button class="btn btn-secondary mx-1">Btn 1</button>
                    <button class="btn btn-secondary mx-1">Btn 2</button>
                    <button class="btn btn-secondary mx-1">Btn 3</button>
                    <button class="btn btn-secondary mx-1">Btn 4</button>
                    <button class="btn btn-secondary mx-1">Btn 5</button>
                    <button id="btn-finalizar" class="btn btn-danger mx-1" type="button">Finalizar</button>
                    <button id="btn-salvar" class="btn btn-success mx-1" style="display:none;" type="button">Salvar Torra</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
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

        headingMonitoramento.style.pointerEvents = 'none';
        headingMonitoramento.style.opacity = '0.5';

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

        form.addEventListener('submit', function(e) {
            console.log('submit interceptado');
            e.preventDefault();

            // Minimiza o formulário
            collapseForm.classList.remove('show');
            iconForm.classList.remove('bi-chevron-down');
            iconForm.classList.add('bi-chevron-right');

            // Habilita e expande o monitoramento
            headingMonitoramento.style.pointerEvents = 'auto';
            headingMonitoramento.style.opacity = '1';
            collapseMonitoramento.classList.add('show');
            iconMonitoramento.classList.remove('bi-chevron-right');
            iconMonitoramento.classList.add('bi-chevron-down');
        });

        btnFinalizar.addEventListener('click', function(e) {
            e.preventDefault();
            btnSalvar.style.display = 'inline-block';
            btnFinalizar.disabled = true;
        });

        btnSalvar.addEventListener('click', function(e) {
            e.preventDefault();
            alert('Torra salva com sucesso!');
        });
    });
</script>
@endsection

