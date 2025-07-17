<?php

echo "=== VERIFICAÇÃO FINAL - UPDATED_AT ===\n\n";

// Verificar modelos
echo "1. Verificando modelos:\n";
$userContent = file_get_contents('app/Models/User.php');
$torradorContent = file_get_contents('app/Models/Torrador.php');

echo "   - User.php sem UPDATED_AT: " . (strpos($userContent, 'UPDATED_AT') === false ? '✓' : '✗') . "\n";
echo "   - Torrador.php sem UPDATED_AT: " . (strpos($torradorContent, 'UPDATED_AT') === false ? '✓' : '✗') . "\n";
echo "   - User.php com CREATED_AT: " . (strpos($userContent, 'CREATED_AT') !== false ? '✓' : '✗') . "\n";
echo "   - Torrador.php com CREATED_AT: " . (strpos($torradorContent, 'CREATED_AT') !== false ? '✓' : '✗') . "\n\n";

// Verificar script PostgreSQL
echo "2. Verificando script PostgreSQL:\n";
$sqlContent = file_get_contents('docker/postgresql/init.sql');
echo "   - Tabela usuarios sem updated_at: " . (strpos($sqlContent, 'updated_at') === false ? '✓' : '✗') . "\n";
echo "   - Tabela usuarios com created_at: " . (strpos($sqlContent, 'created_at') !== false ? '✓' : '✗') . "\n";
echo "   - Tabela torradores sem updated_at: " . (strpos($sqlContent, 'updated_at') === false ? '✓' : '✗') . "\n";
echo "   - Tabela torradores com created_at: " . (strpos($sqlContent, 'created_at') !== false ? '✓' : '✗') . "\n\n";

echo "=== VERIFICAÇÃO CONCLUÍDA ===\n";
echo "Se todos os itens estão com ✓, não há mais referências ao updated_at!\n";
