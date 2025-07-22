// Dashboard do Produtor - Funcionalidades JavaScript

$(document).ready(function() {
    // Configuração inicial
    initializeDashboard();

    // Event listeners
    setupEventListeners();

    // Auto-refresh para dados dinâmicos
    startAutoRefresh();
});

function initializeDashboard() {
    // Inicializar tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();

    // Configurar máscaras de entrada se necessário
    setupInputMasks();

    // Validação do formulário modal
    setupFormValidation();
}

function setupEventListeners() {
    // Modal de solicitação de análise
    $('#modalSolicitarAnalise').on('show.bs.modal', function() {
        clearForm();
        loadAvailableTorras();
    });

    // Validação em tempo real
    $('#torra_id, #analista_id').on('change', function() {
        validateForm();
    });

    // Contador de caracteres para observações
    $('#notas').on('input', function() {
        updateCharacterCount();
    });

    // Confirmação para ações importantes
    $('.btn-danger').on('click', function(e) {
        if (!confirm('Tem certeza que deseja realizar esta ação?')) {
            e.preventDefault();
        }
    });
}

function setupFormValidation() {
    const form = document.getElementById('formSolicitarAnalise');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!validateSolicitacaoForm()) {
                e.preventDefault();
                e.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    }
}

function validateSolicitacaoForm() {
    const torraSelecionada = $('#torra_id').val();
    const analistaSelecionado = $('#analista_id').val();

    if (!torraSelecionada) {
        showAlert('Por favor, selecione uma torra.', 'warning');
        return false;
    }

    if (!analistaSelecionado) {
        showAlert('Por favor, selecione um analista.', 'warning');
        return false;
    }

    return true;
}

function clearForm() {
    $('#formSolicitarAnalise')[0].reset();
    $('#formSolicitarAnalise').removeClass('was-validated');
    updateCharacterCount();
}

function loadAvailableTorras() {
    // Esta função pode ser expandida para carregar torras via AJAX
    // Por enquanto, usa os dados já carregados no PHP
    console.log('Torras carregadas via PHP');
}

function updateCharacterCount() {
    const textarea = $('#notas');
    const maxLength = 500;
    const currentLength = textarea.val().length;
    const remaining = maxLength - currentLength;

    let countElement = $('#character-count');
    if (countElement.length === 0) {
        textarea.after('<div id="character-count" class="form-text text-end"></div>');
        countElement = $('#character-count');
    }

    countElement.text(`${currentLength}/${maxLength} caracteres`);

    if (remaining < 50) {
        countElement.addClass('text-warning');
    } else {
        countElement.removeClass('text-warning text-danger');
    }

    if (remaining < 0) {
        countElement.addClass('text-danger').removeClass('text-warning');
    }
}

function validateForm() {
    const torraValid = $('#torra_id').val() !== '';
    const analistaValid = $('#analista_id').val() !== '';

    $('#btn-enviar-solicitacao').prop('disabled', !(torraValid && analistaValid));

    // Visual feedback
    updateFieldStatus('#torra_id', torraValid);
    updateFieldStatus('#analista_id', analistaValid);
}

function updateFieldStatus(fieldSelector, isValid) {
    const field = $(fieldSelector);
    const feedback = field.siblings('.invalid-feedback, .valid-feedback');

    if (isValid) {
        field.removeClass('is-invalid').addClass('is-valid');
        feedback.hide();
    } else if (field.val() !== '') {
        field.removeClass('is-valid').addClass('is-invalid');
        feedback.show();
    } else {
        field.removeClass('is-valid is-invalid');
        feedback.hide();
    }
}

function startAutoRefresh() {
    // Atualizar estatísticas a cada 30 segundos (opcional)
    // setInterval(refreshStatistics, 30000);
}

function refreshStatistics() {
    // Função para atualizar estatísticas via AJAX
    // Por enquanto, apenas log
    console.log('Atualizando estatísticas...');
}

function showAlert(message, type = 'info', duration = 5000) {
    const alertClass = `alert-${type}`;
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show position-fixed"
             style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;" role="alert">
            <i class="fas fa-${getIconForType(type)} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;

    $('body').append(alertHtml);

    // Auto-remove após duração especificada
    setTimeout(function() {
        $('.alert').fadeOut(function() {
            $(this).remove();
        });
    }, duration);
}

function getIconForType(type) {
    const icons = {
        'success': 'check-circle',
        'danger': 'exclamation-triangle',
        'warning': 'exclamation-circle',
        'info': 'info-circle'
    };
    return icons[type] || 'info-circle';
}

// Função para formatar datas
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('pt-BR', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
    });
}

// Função para animar contadores
function animateCounters() {
    $('.h2').each(function() {
        const $this = $(this);
        const countTo = parseInt($this.text());

        $({ countNum: 0 }).animate({
            countNum: countTo
        }, {
            duration: 1500,
            easing: 'linear',
            step: function() {
                $this.text(Math.floor(this.countNum));
            },
            complete: function() {
                $this.text(countTo);
            }
        });
    });
}

// Executar animação dos contadores quando a página carregar
$(window).on('load', function() {
    setTimeout(animateCounters, 500);
});

// Disponibilizar funções globalmente
window.showAlert = showAlert;
window.formatDate = formatDate;
