-- ========================================
-- TORRA INTELIGENTE - DATABASE SCHEMA
-- Versão: 2.0 - Arquitetura Clean
-- Data: 08/09/2025
-- ========================================

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- ========================================
-- DATABASE CREATION
-- ========================================
CREATE DATABASE IF NOT EXISTS `michelangelo_bd` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `michelangelo_bd`;

-- ========================================
-- TABELA: usuarios
-- ========================================
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `sobrenome` varchar(100) NOT NULL,
  `tipo` enum('Analista','Produtor','Administrador') NOT NULL,
  `email` varchar(150) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_tipo` (`tipo`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ========================================
-- TABELA: qualidade_cafe
-- ========================================
CREATE TABLE IF NOT EXISTS `qualidade_cafe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `variedade` enum('Arábico','Bourbon','Catuai','Mundo Novo','Caturra') NOT NULL,
  `densidade` float NOT NULL,
  `fermentacao` enum('Natural','Fermentado','CD','Honey') NOT NULL,
  `finalidade` enum('Espresso','Filtro','Amostra') NOT NULL,
  `altitude` int(11) DEFAULT NULL,
  `regiao` varchar(100) DEFAULT NULL,
  `safra` varchar(20) DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_variedade` (`variedade`),
  KEY `idx_finalidade` (`finalidade`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ========================================
-- TABELA: torradores (ATUALIZADA)
-- ========================================
CREATE TABLE IF NOT EXISTS `torradores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `codigo_conexao` varchar(32) UNIQUE DEFAULT NULL COMMENT 'Device Key para ESP8266',
  `status` enum('ativo','inativo','manutencao') NOT NULL DEFAULT 'ativo',
  `descricao` text DEFAULT NULL,
  `modelo` varchar(100) DEFAULT NULL,
  `capacidade_kg` decimal(5,2) DEFAULT NULL,
  `temperatura_maxima` decimal(5,2) DEFAULT 250.00,
  `ultima_conexao` timestamp NULL DEFAULT NULL,
  `firmware_version` varchar(20) DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_codigo_conexao` (`codigo_conexao`),
  KEY `fk_usuario` (`usuario_id`),
  KEY `idx_status` (`status`),
  KEY `idx_ultima_conexao` (`ultima_conexao`),
  CONSTRAINT `fk_torradores_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ========================================
-- TABELA: dados_sensores (NOVA)
-- ========================================
CREATE TABLE IF NOT EXISTS `dados_sensores` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `torrador_id` int(11) NOT NULL,
  `temperatura` decimal(5,2) NOT NULL COMMENT 'Temperatura em Celsius',
  `tempo` int(11) NOT NULL DEFAULT 0 COMMENT 'Tempo de torra em segundos',
  `timestamp_esp` bigint(20) NOT NULL COMMENT 'Timestamp do ESP8266',
  `rssi` int(11) DEFAULT NULL COMMENT 'Força do sinal WiFi em dBm',
  `uptime` int(11) DEFAULT NULL COMMENT 'Tempo ligado em segundos',
  `free_heap` int(11) DEFAULT NULL COMMENT 'Memória livre em bytes',
  `version` varchar(10) DEFAULT NULL COMMENT 'Versão do firmware',
  `estado_torra` enum('pre_aquecimento','desenvolvimento','primeiro_crack','segundo_crack','finalizacao') AS (
    CASE 
      WHEN temperatura < 100 THEN 'pre_aquecimento'
      WHEN temperatura < 196 THEN 'desenvolvimento'
      WHEN temperatura < 224 THEN 'primeiro_crack'
      WHEN temperatura < 230 THEN 'segundo_crack'
      ELSE 'finalizacao'
    END
  ) STORED COMMENT 'Estado calculado da torra',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_dados_torrador` (`torrador_id`),
  KEY `idx_temperatura` (`temperatura`),
  KEY `idx_tempo` (`tempo`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_torrador_created` (`torrador_id`, `created_at`),
  KEY `idx_timestamp_esp` (`timestamp_esp`),
  KEY `idx_estado_torra` (`estado_torra`),
  CONSTRAINT `fk_dados_sensores_torrador` FOREIGN KEY (`torrador_id`) REFERENCES `torradores` (`id`) ON DELETE CASCADE,
  CONSTRAINT `chk_temperatura` CHECK (`temperatura` BETWEEN -50 AND 500),
  CONSTRAINT `chk_tempo` CHECK (`tempo` >= 0),
  CONSTRAINT `chk_rssi` CHECK (`rssi` IS NULL OR `rssi` BETWEEN -100 AND 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ========================================
-- TABELA: torras (ATUALIZADA)
-- ========================================
CREATE TABLE IF NOT EXISTS `torras` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `torrador_id` int(11) DEFAULT NULL,
  `nome` varchar(100) NOT NULL,
  `qualidade_cafe_id` int(11) NOT NULL,
  `variedade` varchar(50) DEFAULT NULL,
  `finalidade` enum('Espresso','Filtro','Amostra') DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `status` enum('planejada','em_andamento','concluida','cancelada') DEFAULT 'planejada',
  `data_inicio` timestamp NULL DEFAULT NULL,
  `data_fim` timestamp NULL DEFAULT NULL,
  `temperatura_final` decimal(5,2) DEFAULT NULL,
  `tempo_total` int(11) DEFAULT NULL COMMENT 'Tempo total em segundos',
  `peso_inicial` decimal(6,2) DEFAULT NULL COMMENT 'Peso em gramas',
  `peso_final` decimal(6,2) DEFAULT NULL COMMENT 'Peso em gramas',
  `perda_peso_percent` decimal(5,2) AS (
    CASE 
      WHEN peso_inicial > 0 AND peso_final > 0 
      THEN ((peso_inicial - peso_final) / peso_inicial) * 100 
      ELSE NULL 
    END
  ) STORED COMMENT 'Perda de peso calculada',
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_usuario` (`usuario_id`),
  KEY `fk_qualidade` (`qualidade_cafe_id`),
  KEY `fk_torrador` (`torrador_id`),
  KEY `idx_status` (`status`),
  KEY `idx_data_inicio` (`data_inicio`),
  CONSTRAINT `fk_torras_qualidade` FOREIGN KEY (`qualidade_cafe_id`) REFERENCES `qualidade_cafe` (`id`),
  CONSTRAINT `fk_torras_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_torras_torrador` FOREIGN KEY (`torrador_id`) REFERENCES `torradores` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ========================================
-- TABELA: solicitacoes_prova (ATUALIZADA)
-- ========================================
CREATE TABLE IF NOT EXISTS `solicitacoes_prova` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `solicitante_id` int(11) NOT NULL,
  `analista_id` int(11) DEFAULT NULL,
  `torra_id` int(11) NOT NULL,
  `status` enum('Pendente','Em Analise','Concluida','Rejeitada') NOT NULL DEFAULT 'Pendente',
  `prioridade` enum('baixa','media','alta','urgente') DEFAULT 'media',
  `observacoes_solicitante` text DEFAULT NULL,
  `observacoes_analista` text DEFAULT NULL,
  `data_solicitacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_inicio_analise` timestamp NULL DEFAULT NULL,
  `data_conclusao` timestamp NULL DEFAULT NULL,
  `prazo_desejado` date DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `atualizado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_solicitacoes_solicitante` (`solicitante_id`),
  KEY `fk_solicitacoes_analista` (`analista_id`),
  KEY `fk_solicitacoes_torra` (`torra_id`),
  KEY `idx_status` (`status`),
  KEY `idx_prioridade` (`prioridade`),
  KEY `idx_data_solicitacao` (`data_solicitacao`),
  CONSTRAINT `fk_solicitacoes_solicitante` FOREIGN KEY (`solicitante_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_solicitacoes_analista` FOREIGN KEY (`analista_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_solicitacoes_torra` FOREIGN KEY (`torra_id`) REFERENCES `torras` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ========================================
-- TABELA: analise_sensorial (ATUALIZADA)
-- ========================================
CREATE TABLE IF NOT EXISTS `analise_sensorial` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `solicitacao_id` int(11) NOT NULL,
  `analista_id` int(11) NOT NULL,
  `aroma_po` decimal(3,1) DEFAULT NULL CHECK (`aroma_po` BETWEEN 0 AND 10),
  `fragrancia_cafe` decimal(3,1) DEFAULT NULL CHECK (`fragrancia_cafe` BETWEEN 0 AND 10),
  `aroma_final` decimal(3,1) GENERATED ALWAYS AS ((`aroma_po` + `fragrancia_cafe`) / 2) STORED,
  `sabor` decimal(3,1) DEFAULT NULL CHECK (`sabor` BETWEEN 0 AND 10),
  `acidez` decimal(3,1) DEFAULT NULL CHECK (`acidez` BETWEEN 0 AND 10),
  `corpo` decimal(3,1) DEFAULT NULL CHECK (`corpo` BETWEEN 0 AND 10),
  `retro_gosto` decimal(3,1) DEFAULT NULL CHECK (`retro_gosto` BETWEEN 0 AND 10),
  `equilibrio` decimal(3,1) DEFAULT NULL CHECK (`equilibrio` BETWEEN 0 AND 10),
  `docura` decimal(3,1) DEFAULT NULL CHECK (`docura` BETWEEN 0 AND 10),
  `uniformidade` decimal(3,1) DEFAULT NULL CHECK (`uniformidade` BETWEEN 0 AND 10),
  `defeitos` decimal(3,1) DEFAULT NULL CHECK (`defeitos` BETWEEN 0 AND 10),
  `balanceamento` decimal(3,1) DEFAULT NULL CHECK (`balanceamento` BETWEEN 0 AND 10),
  `nota_final` decimal(5,2) GENERATED ALWAYS AS (
    (`aroma_final` + `sabor` + `acidez` + `corpo` + `retro_gosto` + 
     `equilibrio` + `docura` + `uniformidade` + `defeitos` + `balanceamento`)
  ) STORED,
  `classificacao` enum('Especial','Premium','Comercial','Descarte') AS (
    CASE 
      WHEN ((`aroma_po` + `fragrancia_cafe`) / 2 + `sabor` + `acidez` + `corpo` + `retro_gosto` + 
            `equilibrio` + `docura` + `uniformidade` + `defeitos` + `balanceamento`) >= 80 THEN 'Especial'
      WHEN ((`aroma_po` + `fragrancia_cafe`) / 2 + `sabor` + `acidez` + `corpo` + `retro_gosto` + 
            `equilibrio` + `docura` + `uniformidade` + `defeitos` + `balanceamento`) >= 70 THEN 'Premium'
      WHEN ((`aroma_po` + `fragrancia_cafe`) / 2 + `sabor` + `acidez` + `corpo` + `retro_gosto` + 
            `equilibrio` + `docura` + `uniformidade` + `defeitos` + `balanceamento`) >= 60 THEN 'Comercial'
      ELSE 'Descarte'
    END
  ) STORED,
  `notas_degustacao` text DEFAULT NULL,
  `recomendacoes` text DEFAULT NULL,
  `data_analise` timestamp NOT NULL DEFAULT current_timestamp(),
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_solicitacao_analise` (`solicitacao_id`),
  KEY `fk_analise_analista` (`analista_id`),
  KEY `idx_nota_final` (`nota_final`),
  KEY `idx_classificacao` (`classificacao`),
  KEY `idx_data_analise` (`data_analise`),
  CONSTRAINT `fk_analise_solicitacao` FOREIGN KEY (`solicitacao_id`) REFERENCES `solicitacoes_prova` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_analise_analista` FOREIGN KEY (`analista_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ========================================
-- TABELA: sessoes_torra (NOVA)
-- ========================================
CREATE TABLE IF NOT EXISTS `sessoes_torra` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `torrador_id` int(11) NOT NULL,
  `torra_id` int(11) DEFAULT NULL,
  `nome_sessao` varchar(100) NOT NULL,
  `data_inicio` timestamp NOT NULL,
  `data_fim` timestamp NULL DEFAULT NULL,
  `temperatura_inicial` decimal(5,2) DEFAULT NULL,
  `temperatura_maxima` decimal(5,2) DEFAULT NULL,
  `temperatura_final` decimal(5,2) DEFAULT NULL,
  `duracao_total` int(11) AS (
    CASE 
      WHEN data_fim IS NOT NULL 
      THEN TIMESTAMPDIFF(SECOND, data_inicio, data_fim)
      ELSE NULL 
    END
  ) STORED COMMENT 'Duração em segundos',
  `total_leituras` int(11) DEFAULT 0,
  `status` enum('iniciada','pausada','finalizada','cancelada') DEFAULT 'iniciada',
  `observacoes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_sessoes_torrador` (`torrador_id`),
  KEY `fk_sessoes_torra` (`torra_id`),
  KEY `idx_data_inicio` (`data_inicio`),
  KEY `idx_status` (`status`),
  CONSTRAINT `fk_sessoes_torrador` FOREIGN KEY (`torrador_id`) REFERENCES `torradores` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_sessoes_torra` FOREIGN KEY (`torra_id`) REFERENCES `torras` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ========================================
-- TABELA: alertas_sistema (NOVA)
-- ========================================
CREATE TABLE IF NOT EXISTS `alertas_sistema` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `torrador_id` int(11) NOT NULL,
  `tipo_alerta` enum('temperatura_alta','temperatura_baixa','sinal_fraco','sensor_offline','memoria_baixa') NOT NULL,
  `severidade` enum('info','warning','error','critical') NOT NULL,
  `mensagem` varchar(255) NOT NULL,
  `valor_detectado` decimal(10,2) DEFAULT NULL,
  `limite_configurado` decimal(10,2) DEFAULT NULL,
  `dados_sensor_id` bigint(20) DEFAULT NULL,
  `resolvido` tinyint(1) DEFAULT 0,
  `resolvido_em` timestamp NULL DEFAULT NULL,
  `resolvido_por` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_alertas_torrador` (`torrador_id`),
  KEY `fk_alertas_dados_sensor` (`dados_sensor_id`),
  KEY `fk_alertas_resolvido_por` (`resolvido_por`),
  KEY `idx_tipo_alerta` (`tipo_alerta`),
  KEY `idx_severidade` (`severidade`),
  KEY `idx_resolvido` (`resolvido`),
  KEY `idx_created_at` (`created_at`),
  CONSTRAINT `fk_alertas_torrador` FOREIGN KEY (`torrador_id`) REFERENCES `torradores` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_alertas_dados_sensor` FOREIGN KEY (`dados_sensor_id`) REFERENCES `dados_sensores` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_alertas_resolvido_por` FOREIGN KEY (`resolvido_por`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ========================================
-- VIEWS ÚTEIS
-- ========================================

-- View: Dados em tempo real dos torradores
CREATE OR REPLACE VIEW `vw_torradores_status` AS
SELECT 
    t.id,
    t.nome,
    t.codigo_conexao,
    t.status,
    t.ultima_conexao,
    CASE 
        WHEN t.ultima_conexao >= DATE_SUB(NOW(), INTERVAL 5 MINUTE) THEN 'online'
        WHEN t.ultima_conexao >= DATE_SUB(NOW(), INTERVAL 1 HOUR) THEN 'recente'
        ELSE 'offline'
    END as conexao_status,
    ds.temperatura as ultima_temperatura,
    ds.tempo as tempo_atual,
    ds.created_at as ultima_leitura,
    u.nome as usuario_nome
FROM torradores t
LEFT JOIN usuarios u ON t.usuario_id = u.id
LEFT JOIN dados_sensores ds ON ds.id = (
    SELECT id FROM dados_sensores 
    WHERE torrador_id = t.id 
    ORDER BY created_at DESC 
    LIMIT 1
);

-- View: Resumo de análises sensoriais
CREATE OR REPLACE VIEW `vw_analises_resumo` AS
SELECT 
    a.id,
    a.nota_final,
    a.classificacao,
    a.data_analise,
    t.nome as torra_nome,
    qc.variedade,
    qc.finalidade,
    u_prod.nome as produtor_nome,
    u_anal.nome as analista_nome,
    sp.status as status_solicitacao
FROM analise_sensorial a
JOIN solicitacoes_prova sp ON a.solicitacao_id = sp.id
JOIN torras t ON sp.torra_id = t.id
JOIN qualidade_cafe qc ON t.qualidade_cafe_id = qc.id
JOIN usuarios u_prod ON t.usuario_id = u_prod.id
JOIN usuarios u_anal ON a.analista_id = u_anal.id;

-- ========================================
-- TRIGGERS
-- ========================================

-- Trigger: Atualizar última conexão do torrador
DELIMITER $$
CREATE TRIGGER `tr_dados_sensores_after_insert` 
AFTER INSERT ON `dados_sensores`
FOR EACH ROW
BEGIN
    UPDATE torradores 
    SET ultima_conexao = NEW.created_at,
        firmware_version = NEW.version
    WHERE id = NEW.torrador_id;
END$$

-- Trigger: Criar alerta automático para temperatura crítica
CREATE TRIGGER `tr_alerta_temperatura_critica` 
AFTER INSERT ON `dados_sensores`
FOR EACH ROW
BEGIN
    IF NEW.temperatura > 240 THEN
        INSERT INTO alertas_sistema (
            torrador_id, 
            tipo_alerta, 
            severidade, 
            mensagem, 
            valor_detectado, 
            limite_configurado,
            dados_sensor_id
        ) VALUES (
            NEW.torrador_id,
            'temperatura_alta',
            'critical',
            CONCAT('Temperatura crítica detectada: ', NEW.temperatura, '°C'),
            NEW.temperatura,
            240.00,
            NEW.id
        );
    END IF;
END$$

-- Trigger: Atualizar status da solicitação quando análise é criada
CREATE TRIGGER `tr_analise_sensorial_after_insert` 
AFTER INSERT ON `analise_sensorial`
FOR EACH ROW
BEGIN
    UPDATE solicitacoes_prova 
    SET status = 'Concluida',
        data_conclusao = NEW.created_at
    WHERE id = NEW.solicitacao_id;
END$$

DELIMITER ;

-- ========================================
-- DADOS INICIAIS
-- ========================================

-- Inserir usuários de exemplo (mantendo os existentes)
INSERT INTO `usuarios` (`id`, `nome`, `sobrenome`, `tipo`, `email`, `senha`, `criado_em`) VALUES
(7, 'joao', 'cardoso', 'Administrador', 'carlos@gmail', '$2y$12$RRpMC9JaT7sxlFNDhSA5leNd7FQlIsrbWvo.B51eDnmm9AJdj1XLy', '2025-06-17 12:18:33'),
(8, 'joao', 'cardoso', 'Analista', 'dunthone2016@gmail.com', '$2y$12$G7ep4iMybO23V15SDG4//.xDLdKdZnwA1PzE/fvTFqXl0uJpeUIeG', '2025-06-17 12:27:26')
ON DUPLICATE KEY UPDATE id = VALUES(id);

-- Inserir qualidades de café de exemplo
INSERT INTO `qualidade_cafe` (`variedade`, `densidade`, `fermentacao`, `finalidade`, `altitude`, `regiao`, `safra`) VALUES
('Arábico', 1.20, 'Natural', 'Espresso', 1200, 'Sul de Minas', '2025'),
('Bourbon', 1.25, 'Fermentado', 'Filtro', 1100, 'Cerrado', '2025'),
('Catuai', 1.18, 'Honey', 'Amostra', 1300, 'Mantiqueira', '2025');

-- Inserir torradores de exemplo
INSERT INTO `torradores` (`usuario_id`, `nome`, `codigo_conexao`, `status`, `descricao`, `modelo`, `capacidade_kg`, `temperatura_maxima`) VALUES
(7, 'Torrador Principal', 'TI_2025_A1B2C3D4', 'ativo', 'Torrador principal da fazenda', 'San Franciscan SF-6', 2.50, 250.00),
(8, 'Torrador de Testes', 'TI_2025_E5F6G7H8', 'ativo', 'Usado para testes e amostras', 'Probat Sample Roaster', 0.30, 230.00);

-- ========================================
-- ÍNDICES ADICIONAIS PARA PERFORMANCE
-- ========================================

-- Índices compostos para consultas frequentes
CREATE INDEX `idx_dados_sensores_temp_tempo` ON `dados_sensores` (`temperatura`, `tempo`);
CREATE INDEX `idx_analise_nota_classificacao` ON `analise_sensorial` (`nota_final`, `classificacao`);
CREATE INDEX `idx_torras_usuario_status` ON `torras` (`usuario_id`, `status`);
CREATE INDEX `idx_solicitacoes_status_data` ON `solicitacoes_prova` (`status`, `data_solicitacao`);

-- ========================================
-- PROCEDURES ÚTEIS
-- ========================================

DELIMITER $$

-- Procedure: Obter estatísticas de um torrador
CREATE PROCEDURE `sp_estatisticas_torrador`(IN p_torrador_id INT, IN p_periodo VARCHAR(10))
BEGIN
    DECLARE data_limite DATETIME;
    
    SET data_limite = CASE 
        WHEN p_periodo = '1h' THEN DATE_SUB(NOW(), INTERVAL 1 HOUR)
        WHEN p_periodo = '24h' THEN DATE_SUB(NOW(), INTERVAL 1 DAY)
        WHEN p_periodo = '7d' THEN DATE_SUB(NOW(), INTERVAL 7 DAY)
        WHEN p_periodo = '30d' THEN DATE_SUB(NOW(), INTERVAL 30 DAY)
        ELSE DATE_SUB(NOW(), INTERVAL 1 DAY)
    END;
    
    SELECT 
        COUNT(*) as total_leituras,
        ROUND(AVG(temperatura), 2) as temperatura_media,
        ROUND(MAX(temperatura), 2) as temperatura_maxima,
        ROUND(MIN(temperatura), 2) as temperatura_minima,
        MAX(tempo) as tempo_maximo,
        COUNT(CASE WHEN temperatura > 240 THEN 1 END) as leituras_criticas,
        ROUND(AVG(rssi), 0) as rssi_medio
    FROM dados_sensores 
    WHERE torrador_id = p_torrador_id 
      AND created_at >= data_limite;
END$$

-- Procedure: Limpar dados antigos
CREATE PROCEDURE `sp_limpar_dados_antigos`(IN p_dias_manter INT)
BEGIN
    DECLARE dados_removidos INT DEFAULT 0;
    DECLARE alertas_removidos INT DEFAULT 0;
    
    -- Remover dados de sensores antigos
    DELETE FROM dados_sensores 
    WHERE created_at < DATE_SUB(NOW(), INTERVAL p_dias_manter DAY);
    SET dados_removidos = ROW_COUNT();
    
    -- Remover alertas resolvidos antigos
    DELETE FROM alertas_sistema 
    WHERE resolvido = 1 
      AND resolvido_em < DATE_SUB(NOW(), INTERVAL p_dias_manter DAY);
    SET alertas_removidos = ROW_COUNT();
    
    SELECT 
        dados_removidos as dados_sensores_removidos,
        alertas_removidos as alertas_removidos,
        CONCAT('Limpeza concluída: ', dados_removidos + alertas_removidos, ' registros removidos') as mensagem;
END$$

DELIMITER ;

-- ========================================
-- RESTORE CONFIGURATIONS
-- ========================================
/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;

-- ========================================
-- FIM DO SCRIPT
-- ========================================
