<?php
try {
    $pdo = new PDO('pgsql:host=127.0.0.1;port=5432;dbname=michelangelo_bd_local', 'postgres', 'postgres');
    echo "Conexão PostgreSQL OK\n";

    $stmt = $pdo->query("SELECT version()");
    $version = $stmt->fetchColumn();
    echo "PostgreSQL version: " . $version . "\n";

} catch(Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}
?>
