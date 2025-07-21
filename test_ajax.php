<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Http\Controllers\PerfilController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

// Limpar logs
if (file_exists('storage/logs/laravel.log')) {
    file_put_contents('storage/logs/laravel.log', '');
}

// Simular autenticação
$user = User::find(1);
Auth::login($user);

echo "=== TESTE AJAX DO PERFIL ===\n";
echo "Usuário autenticado: " . Auth::user()->nome . "\n\n";

// Criar uma request simulada como AJAX
$request = new Request();
$request->merge([
    'nome' => 'João AJAX Teste',
    'sobrenome' => 'Cardoso AJAX',
    'email' => 'ajax@teste.com',
    'telefone' => '987654321',
    'tipo' => 'Produtor'
]);

// Adicionar headers AJAX
$request->headers->set('X-Requested-With', 'XMLHttpRequest');
$request->headers->set('Content-Type', 'application/x-www-form-urlencoded');
$request->headers->set('Accept', 'application/json');

// Adicionar token CSRF
$request->merge(['_token' => 'test_token']);

echo "Dados da request:\n";
print_r($request->all());
echo "\nHeaders:\n";
echo "X-Requested-With: " . $request->header('X-Requested-With') . "\n";
echo "Accept: " . $request->header('Accept') . "\n";
echo "É AJAX? " . ($request->ajax() ? 'SIM' : 'NÃO') . "\n";
echo "Quer JSON? " . ($request->wantsJson() ? 'SIM' : 'NÃO') . "\n\n";

// Executar o controller
try {
    $controller = new PerfilController();
    $response = $controller->update($request);

    echo "Response status: " . $response->getStatusCode() . "\n";
    echo "Response headers:\n";
    foreach ($response->headers->all() as $key => $values) {
        echo "  $key: " . implode(', ', $values) . "\n";
    }
    echo "Response content: " . $response->getContent() . "\n";

} catch (Exception $e) {
    echo "ERRO: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== VERIFICANDO LOGS ===\n";
if (file_exists('storage/logs/laravel.log')) {
    $logs = file_get_contents('storage/logs/laravel.log');
    if (!empty($logs)) {
        echo $logs;
    } else {
        echo "Logs vazios.\n";
    }
} else {
    echo "Nenhum arquivo de log encontrado.\n";
}
