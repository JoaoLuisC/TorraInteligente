<?php
// Debug script para verificar o que está causando o erro 500

// Mostrar todos os erros
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== DEBUG TORRA INTELIGENTE ===\n";

try {
    // Verificar se o Laravel consegue carregar
    echo "1. Carregando Laravel...\n";
    require_once __DIR__ . '/vendor/autoload.php';
    echo "✅ Autoload OK\n";

    // Verificar se consegue criar a aplicação
    echo "2. Criando aplicação Laravel...\n";
    $app = require_once __DIR__ . '/bootstrap/app.php';
    echo "✅ App bootstrap OK\n";

    // Verificar configurações
    echo "3. Verificando configurações...\n";
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    echo "✅ Kernel bootstrap OK\n";

    // Verificar banco de dados
    echo "4. Testando conexão com banco...\n";

    // Verificar se extensões PostgreSQL estão carregadas
    echo "PostgreSQL extension loaded: " . (extension_loaded('pgsql') ? 'YES' : 'NO') . "\n";
    echo "PDO PostgreSQL extension loaded: " . (extension_loaded('pdo_pgsql') ? 'YES' : 'NO') . "\n";

    if (extension_loaded('pdo_pgsql')) {
        echo "✅ Driver PostgreSQL encontrado\n";

        // Usar Laravel DB ao invés de PDO direto
        try {
            $pdo = DB::connection()->getPdo();
            echo "✅ Conexão com banco OK\n";

            // Verificar se consegue fazer uma query simples
            echo "5. Testando query no banco...\n";
            $result = DB::select('SELECT 1 as test');
            echo "✅ Query OK: " . $result[0]->test . "\n";
        } catch (Exception $dbError) {
            echo "❌ ERRO de banco: " . $dbError->getMessage() . "\n";
        }
    } else {
        echo "❌ Driver PostgreSQL NÃO encontrado\n";
    }

    // Verificar cache e storage
    echo "6. Verificando diretórios...\n";
    echo "Storage exists: " . (is_dir(__DIR__ . '/storage') ? 'YES' : 'NO') . "\n";
    echo "Bootstrap/cache exists: " . (is_dir(__DIR__ . '/bootstrap/cache') ? 'YES' : 'NO') . "\n";
    echo "Storage writable: " . (is_writable(__DIR__ . '/storage') ? 'YES' : 'NO') . "\n";
    echo "Bootstrap/cache writable: " . (is_writable(__DIR__ . '/bootstrap/cache') ? 'YES' : 'NO') . "\n";

    echo "\n=== TUDO OK ATÉ AQUI ===\n";

} catch (Exception $e) {
    echo "❌ ERRO: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== VARIÁVEIS DE AMBIENTE ===\n";
echo "APP_ENV: " . ($_ENV['APP_ENV'] ?? 'não definido') . "\n";
echo "APP_KEY: " . (isset($_ENV['APP_KEY']) ? 'definido' : 'NÃO DEFINIDO') . "\n";
echo "DB_CONNECTION: " . ($_ENV['DB_CONNECTION'] ?? 'não definido') . "\n";
echo "DATABASE_URL: " . (isset($_ENV['DATABASE_URL']) ? 'definido' : 'NÃO DEFINIDO') . "\n";
echo "DB_HOST: " . ($_ENV['DB_HOST'] ?? 'não definido') . "\n";
echo "DB_PORT: " . ($_ENV['DB_PORT'] ?? 'não definido') . "\n";
echo "DB_DATABASE: " . ($_ENV['DB_DATABASE'] ?? 'não definido') . "\n";
echo "DB_USERNAME: " . ($_ENV['DB_USERNAME'] ?? 'não definido') . "\n";

echo "\n=== CONFIGURAÇÃO ATUAL DO BANCO ===\n";
try {
    $dbConfig = config('database.connections.pgsql');
    echo "Host configurado: " . $dbConfig['host'] . "\n";
    echo "Port configurado: " . $dbConfig['port'] . "\n";
    echo "Database configurado: " . $dbConfig['database'] . "\n";
    echo "Username configurado: " . $dbConfig['username'] . "\n";
    echo "URL configurado: " . ($dbConfig['url'] ?? 'não definido') . "\n";
} catch (Exception $e) {
    echo "❌ Erro ao ler configuração: " . $e->getMessage() . "\n";
}
?>
