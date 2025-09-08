<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\TorradorController;
use App\Http\Controllers\TorrasController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\ProdutorController;

// Home - Página principal do site
Route::get('/', function () {
    return view('home');
})->name('home');

// Cadastro (Register)
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Torradores
Route::middleware('auth')->group(function () {

    // Monitor de Sensores - Tempo Real
    Route::get('/monitor/realtime', [App\Http\Controllers\MonitorController::class, 'realtime'])->name('monitor.realtime');
    Route::get('/monitor/dashboard', [App\Http\Controllers\MonitorController::class, 'dashboard'])->name('monitor.dashboard');
    Route::get('/monitor/historico', [App\Http\Controllers\MonitorController::class, 'historico'])->name('monitor.historico');

    // Debug route - temporária para solicitações pendentes
    Route::get('/debug-pendentes', function () {
        $pendentes = DB::table('solicitacoes_prova')->where('status', 'Pendente')->get();
        $analises = DB::table('analise_sensorial')->get();

        // Query exata do dashboard
        $dashboardQuery = DB::table('solicitacoes_prova as sp')
            ->leftJoin('analise_sensorial as a', 'sp.id', '=', 'a.solicitacao_id')
            ->join('torras as t', 'sp.torra_id', '=', 't.id')
            ->join('usuarios as produtor', 't.usuario_id', '=', 'produtor.id')
            ->where('sp.status', 'Pendente')
            ->whereNull('a.id')
            ->select(
                'sp.*',
                't.nome as torra_nome',
                't.variedade',
                't.finalidade',
                'produtor.nome as produtor_nome'
            )
            ->orderBy('sp.criado_em', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'total_pendentes_simples' => $pendentes->count(),
            'pendentes_simples' => $pendentes,
            'dashboard_query_count' => $dashboardQuery->count(),
            'dashboard_query_result' => $dashboardQuery,
            'total_analises' => $analises->count(),
            'analises' => $analises
        ]);
    });

    Route::get('/dashboard', function () {
        $user = Auth::user();

        if ($user->tipo === 'Administrador') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->tipo === 'Analista') {
            return redirect()->route('analise.dashboard');
        } else { // Produtor
            return app(ProdutorController::class)->dashboard();
        }
    })->name('dashboard');
    Route::get('/perfil', [PerfilController::class, 'index'])->name('perfil');
    Route::get('/perfil/editar', [PerfilController::class, 'edit'])->name('perfil.edit');
    Route::put('/perfil', [PerfilController::class, 'update'])->name('perfil.update');
    Route::delete('/perfil/imagem', [PerfilController::class, 'removerImagem'])->name('perfil.remover-imagem');
    Route::get('/perfil/alterar-senha', [PerfilController::class, 'showAlterarSenha'])->name('perfil.alterar-senha');
    Route::put('/perfil/senha', [PerfilController::class, 'alterarSenha'])->name('perfil.senha.update');

    // Rotas específicas do Produtor
    Route::post('/produtor/solicitar-analise', [ProdutorController::class, 'solicitarAnalise'])->name('produtor.solicitar-analise');

    Route::prefix('torradores')->group(function () {
        Route::get('/', [TorradorController::class, 'index'])->name('torradores.index');
        Route::get('/adicionar-torrador', [TorradorController::class, 'create'])->name('torradores.adicionar-sensor');
        Route::post('/adicionar-torrador', [TorradorController::class, 'store'])->name('torradores.store');
        Route::get('/{id}', [TorradorController::class, 'show'])->name('torradores.show');
        Route::get('/{id}/editar', [TorradorController::class, 'edit'])->name('torradores.edit');
        Route::put('/{id}/editar', [TorradorController::class, 'update'])->name('torradores.update');
        Route::delete('/{id}', [TorradorController::class, 'destroy'])->name('torradores.destroy');
    });

    Route::prefix('torras')->name('torras.')->group(function () {
        Route::get('/', [TorrasController::class, 'index'])->name('index');
        Route::get('/criar', [TorrasController::class, 'create'])->name('create');
        Route::get('/iniciar', [TorrasController::class, 'iniciar'])->name('iniciar');
        Route::post('/iniciar', [TorrasController::class, 'store'])->name('store');
        Route::get('/monitoramento', [TorrasController::class, 'monitoramento'])->name('monitoramento');
        Route::get('/solicitar-avaliacao', [TorrasController::class, 'mostrarSolicitarAvaliacao'])->name('solicitar-avaliacao');
        Route::post('/solicitar-avaliacao', [TorrasController::class, 'processarSolicitarAvaliacao'])->name('solicitar-avaliacao.processar');
        Route::delete('/{id}', [TorrasController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/solicitacoes', [TorrasController::class, 'getSolicitacoes'])->name('solicitacoes');
        Route::post('/{id}/solicitar-avaliacao-individual', [TorrasController::class, 'solicitarAvaliacao'])->name('solicitar-avaliacao.individual');
        Route::put('/{torraId}/solicitacoes/{solicitacaoId}/cancelar', [TorrasController::class, 'cancelarSolicitacao'])->name('solicitacoes.cancelar');
        // Adicione outras rotas de torras aqui, se houver
    });

    // Rotas de Análise (somente para Analistas)
    Route::prefix('analise')->name('analise.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\AnaliseController::class, 'dashboard'])->name('dashboard');
        Route::get('/pendentes', [App\Http\Controllers\AnaliseController::class, 'pendentes'])->name('pendentes');
        Route::get('/historico', [App\Http\Controllers\AnaliseController::class, 'historico'])->name('historico');
        Route::get('/analisar/{id}', [App\Http\Controllers\AnaliseController::class, 'analisar'])->name('analisar');
        Route::post('/analisar/{id}', [App\Http\Controllers\AnaliseController::class, 'salvarAnalise'])->name('salvar');
    });

    // Rotas de Administração (somente para Administradores)
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/usuarios', [App\Http\Controllers\AdminController::class, 'usuarios'])->name('usuarios');
        Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
        Route::delete('/usuarios/{id}', [App\Http\Controllers\AdminController::class, 'excluirUsuario'])->name('usuarios.excluir');
    });

});

