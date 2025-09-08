<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

use Illuminate\Support\Facades\DB;

try {
    // Teste de conexão
    $pdo = DB::connection()->getPdo();
    echo "✅ Conexão com PostgreSQL estabelecida!\n";

    // Teste de consulta na tabela usuarios
    $users = DB::table('usuarios')->get();
    echo "✅ Consulta na tabela 'usuarios' funcionando!\n";
    echo "📊 Total de usuários: " . $users->count() . "\n";

} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
    echo "🔍 Detalhes: " . $e->getTraceAsString() . "\n";
}
