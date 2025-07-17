<?php

echo "=== TESTE DE CONFIGURAÇÃO ===\n\n";

// Teste 1: Verificar arquivos críticos
echo "1. Verificando arquivos críticos:\n";
echo "   - User.php: " . (file_exists('app/Models/User.php') ? '✓' : '✗') . "\n";
echo "   - Torrador.php: " . (file_exists('app/Models/Torrador.php') ? '✓' : '✗') . "\n";
echo "   - RegisterController.php: " . (file_exists('app/Http/Controllers/Auth/RegisterController.php') ? '✓' : '✗') . "\n";
echo "   - LoginController.php: " . (file_exists('app/Http/Controllers/Auth/LoginController.php') ? '✓' : '✗') . "\n\n";

// Teste 2: Verificar configuração do User model
echo "2. Verificando User model:\n";
$userContent = file_get_contents('app/Models/User.php');
echo "   - Tabela 'usuarios': " . (strpos($userContent, "protected \$table = 'usuarios'") !== false ? '✓' : '✗') . "\n";
echo "   - Campo 'nome' fillable: " . (strpos($userContent, "'nome'") !== false ? '✓' : '✗') . "\n";
echo "   - Campo 'senha' fillable: " . (strpos($userContent, "'senha'") !== false ? '✓' : '✗') . "\n";
echo "   - Método getAuthPassword: " . (strpos($userContent, 'getAuthPassword') !== false ? '✓' : '✗') . "\n\n";

// Teste 3: Verificar RegisterController
echo "3. Verificando RegisterController:\n";
$registerContent = file_get_contents('app/Http/Controllers/Auth/RegisterController.php');
echo "   - Validação 'usuarios': " . (strpos($registerContent, "unique:usuarios,email") !== false ? '✓' : '✗') . "\n";
echo "   - Campo 'nome': " . (strpos($registerContent, "'nome' => ") !== false ? '✓' : '✗') . "\n";
echo "   - Campo 'senha': " . (strpos($registerContent, "'senha' => ") !== false ? '✓' : '✗') . "\n\n";

// Teste 4: Verificar LoginController
echo "4. Verificando LoginController:\n";
$loginContent = file_get_contents('app/Http/Controllers/Auth/LoginController.php');
echo "   - Credencial 'senha': " . (strpos($loginContent, "'senha' => ") !== false ? '✓' : '✗') . "\n\n";

// Teste 5: Verificar migração torradors
echo "5. Verificando migração torradors:\n";
$migrationContent = file_get_contents('database/migrations/2025_07_12_223604_create_torradors_table.php');
echo "   - Referência 'usuarios': " . (strpos($migrationContent, "->on('usuarios')") !== false ? '✓' : '✗') . "\n\n";

echo "=== TESTE CONCLUÍDO ===\n";
echo "Se todos os itens estão com ✓, o sistema deve funcionar!\n";
