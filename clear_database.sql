-- Script para limpar todos os dados do banco de dados
-- Mantém a estrutura das tabelas, remove apenas os dados

-- Desabilitar verificação de chaves estrangeiras temporariamente
SET session_replication_role = replica;

-- Limpar dados das tabelas na ordem correta (respeitando foreign keys)
TRUNCATE TABLE analise_sensorial RESTART IDENTITY CASCADE;
TRUNCATE TABLE solicitacoes_prova RESTART IDENTITY CASCADE;
TRUNCATE TABLE torras RESTART IDENTITY CASCADE;
TRUNCATE TABLE torradores RESTART IDENTITY CASCADE;
TRUNCATE TABLE usuarios RESTART IDENTITY CASCADE;

-- Reabilitar verificação de chaves estrangeiras
SET session_replication_role = DEFAULT;

-- Verificar se as tabelas estão vazias
SELECT 'usuarios' as tabela, COUNT(*) as registros FROM usuarios
UNION ALL
SELECT 'torradores' as tabela, COUNT(*) as registros FROM torradores
UNION ALL
SELECT 'torras' as tabela, COUNT(*) as registros FROM torras
UNION ALL
SELECT 'solicitacoes_prova' as tabela, COUNT(*) as registros FROM solicitacoes_prova
UNION ALL
SELECT 'analise_sensorial' as tabela, COUNT(*) as registros FROM analise_sensorial;
