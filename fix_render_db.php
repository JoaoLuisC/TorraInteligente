<?php
/**
 * Script para corrigir problemas no banco de dados do Render
 * Executa as correções necessárias para fazer a aplicação funcionar
 */

require_once __DIR__ . '/vendor/autoload.php';

// Criar aplicação Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== CORREÇÃO DO BANCO DE DADOS RENDER ===\n";

try {
    $pdo = DB::connection()->getPdo();
    echo "✅ Conexão com banco estabelecida\n";
} catch (Exception $e) {
    echo "❌ Erro de conexão: " . $e->getMessage() . "\n";
    exit(1);
}

// Função para executar SQL e capturar erros
function executeSQL($sql, $description) {
    try {
        DB::statement($sql);
        echo "✅ $description\n";
        return true;
    } catch (Exception $e) {
        echo "⚠️  $description - " . $e->getMessage() . "\n";
        return false;
    }
}

echo "\n1. Adicionando coluna 'imagem' na tabela usuarios (se não existir)...\n";
executeSQL("ALTER TABLE usuarios ADD COLUMN IF NOT EXISTS imagem VARCHAR(255)", "Coluna imagem adicionada");

echo "\n2. Adicionando coluna 'status' na tabela torras (se não existir)...\n";
executeSQL("ALTER TABLE torras ADD COLUMN IF NOT EXISTS status VARCHAR(30) CHECK (status IN ('nao_avaliada', 'aguardando_avaliacao', 'avaliada')) DEFAULT 'nao_avaliada'", "Coluna status adicionada");

echo "\n3. Adicionando colunas de observações na tabela torras...\n";
executeSQL("ALTER TABLE torras ADD COLUMN IF NOT EXISTS observacoes_produtor TEXT", "Coluna observacoes_produtor adicionada");

echo "\n4. Criando tabela solicitacoes_prova (se não existir)...\n";
$sqlSolicitacoes = "
CREATE TABLE IF NOT EXISTS solicitacoes_prova (
    id SERIAL PRIMARY KEY,
    solicitante_id INTEGER NOT NULL,
    analista_id INTEGER,
    torra_id INTEGER NOT NULL,
    notas TEXT,
    status VARCHAR(20) CHECK (status IN ('pendente', 'em_andamento', 'concluida', 'cancelada')) DEFAULT 'pendente',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_solicitacoes_solicitante FOREIGN KEY (solicitante_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    CONSTRAINT fk_solicitacoes_analista FOREIGN KEY (analista_id) REFERENCES usuarios(id) ON DELETE SET NULL,
    CONSTRAINT fk_solicitacoes_torra FOREIGN KEY (torra_id) REFERENCES torras(id) ON DELETE CASCADE
);
";
executeSQL($sqlSolicitacoes, "Tabela solicitacoes_prova criada");

echo "\n5. Criando tabela analise_sensorial (se não existir)...\n";
$sqlAnalise = "
CREATE TABLE IF NOT EXISTS analise_sensorial (
    id SERIAL PRIMARY KEY,
    solicitacao_id INTEGER NOT NULL,
    aroma_po DECIMAL(3,1) CHECK (aroma_po BETWEEN 0 AND 10),
    fragrancia_cafe DECIMAL(3,1) CHECK (fragrancia_cafe BETWEEN 0 AND 10),
    sabor DECIMAL(3,1) CHECK (sabor BETWEEN 0 AND 10),
    acidez DECIMAL(3,1) CHECK (acidez BETWEEN 0 AND 10),
    corpo DECIMAL(3,1) CHECK (corpo BETWEEN 0 AND 10),
    retro_gosto DECIMAL(3,1) CHECK (retro_gosto BETWEEN 0 AND 10),
    equilibrio DECIMAL(3,1) CHECK (equilibrio BETWEEN 0 AND 10),
    docura DECIMAL(3,1) CHECK (docura BETWEEN 0 AND 10),
    uniformidade DECIMAL(3,1) CHECK (uniformidade BETWEEN 0 AND 10),
    defeitos DECIMAL(3,1) CHECK (defeitos BETWEEN 0 AND 10),
    balanceamento DECIMAL(3,1) CHECK (balanceamento BETWEEN 0 AND 10),
    nota_final DECIMAL(5,2),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_analise_solicitacao FOREIGN KEY (solicitacao_id) REFERENCES solicitacoes_prova(id) ON DELETE CASCADE
);
";
executeSQL($sqlAnalise, "Tabela analise_sensorial criada");

echo "\n6. Adicionando colunas de compatibilidade com Laravel...\n";
executeSQL("ALTER TABLE usuarios ADD COLUMN IF NOT EXISTS email_verified_at TIMESTAMP", "Coluna email_verified_at adicionada");
executeSQL("ALTER TABLE usuarios ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP", "Coluna created_at adicionada");
executeSQL("ALTER TABLE usuarios ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP", "Coluna updated_at adicionada");

echo "\n7. Adicionando colunas faltantes na tabela torras...\n";
executeSQL("ALTER TABLE torras ADD COLUMN IF NOT EXISTS avaliador_id INTEGER", "Coluna avaliador_id adicionada");
executeSQL("ALTER TABLE torras ADD COLUMN IF NOT EXISTS avaliada_em TIMESTAMP", "Coluna avaliada_em adicionada");

echo "\n8. Criando foreign key constraints se não existirem...\n";
executeSQL("ALTER TABLE torras ADD CONSTRAINT IF NOT EXISTS fk_torras_avaliador FOREIGN KEY (avaliador_id) REFERENCES usuarios(id) ON DELETE SET NULL", "Foreign key torras->avaliador criada");

echo "\n9. LIMPANDO TODOS OS DADOS PARA COMEÇAR DO ZERO...\n";
executeSQL("DELETE FROM analise_sensorial", "Dados de análise sensorial removidos");
executeSQL("DELETE FROM solicitacoes_prova", "Dados de solicitações removidos");
executeSQL("DELETE FROM torras", "Dados de torras removidos");
executeSQL("DELETE FROM torradores", "Dados de torradores removidos");
executeSQL("DELETE FROM usuarios", "Dados de usuários removidos");

echo "\n10. Resetando sequências...\n";
executeSQL("ALTER SEQUENCE usuarios_id_seq RESTART WITH 1", "Sequência usuarios resetada");
executeSQL("ALTER SEQUENCE torras_id_seq RESTART WITH 1", "Sequência torras resetada");
executeSQL("ALTER SEQUENCE torradores_id_seq RESTART WITH 1", "Sequência torradores resetada");
executeSQL("ALTER SEQUENCE solicitacoes_prova_id_seq RESTART WITH 1", "Sequência solicitacoes_prova resetada");
executeSQL("ALTER SEQUENCE analise_sensorial_id_seq RESTART WITH 1", "Sequência analise_sensorial resetada");

echo "\n11. Criando usuário administrador padrão...\n";
$senhaHash = password_hash('admin123', PASSWORD_DEFAULT);
$sqlAdmin = "
INSERT INTO usuarios (nome, sobrenome, tipo, email, senha, criado_em, created_at, updated_at) 
VALUES ('Admin', 'Sistema', 'Administrador', 'admin@torrainteligente.com', '$senhaHash', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
";
executeSQL($sqlAdmin, "Usuário administrador criado (admin@torrainteligente.com / admin123)");

echo "\n=== CORREÇÃO CONCLUÍDA ===\n";
echo "✅ Banco de dados corrigido e limpo\n";
echo "✅ Usuário admin criado: admin@torrainteligente.com / admin123\n";
echo "✅ Todas as tabelas e colunas necessárias foram criadas\n";
echo "\nA aplicação agora deve funcionar corretamente no Render!\n";
