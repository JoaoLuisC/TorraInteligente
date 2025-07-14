-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           10.4.32-MariaDB - mariadb.org binary distribution
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              12.10.0.7000
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Copiando estrutura do banco de dados para michelangelo_bd
CREATE DATABASE IF NOT EXISTS `michelangelo_bd` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `michelangelo_bd`;

-- Copiando estrutura para tabela michelangelo_bd.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `sobrenome` varchar(100) NOT NULL,
  `tipo` enum('Analista','Produtor','Administrador') NOT NULL,
  `email` varchar(150) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela michelangelo_bd.usuarios: ~3 rows (aproximadamente)
INSERT INTO `usuarios` (`id`, `nome`, `sobrenome`, `tipo`, `email`, `senha`, `criado_em`) VALUES
	(7, 'joao', 'cardoso', 'Administrador', 'carlos@gmail', '$2y$12$RRpMC9JaT7sxlFNDhSA5leNd7FQlIsrbWvo.B51eDnmm9AJdj1XLy', '2025-06-17 12:18:33'),
	(8, 'joao', 'cardoso', 'Analista', 'dunthone2016@gmail.com', '$2y$12$G7ep4iMybO23V15SDG4//.xDLdKdZnwA1PzE/fvTFqXl0uJpeUIeG', '2025-06-17 12:27:26'),
	(9, 'carlos', 'guedes', 'Analista', 'carlao@gmail.com', '$2y$12$tstUPTb2klBxjrpt1lKYF.lW2LwV86PonBdU7jvySbpFuyLrJYf8a', '2025-06-17 23:53:10');

-- Copiando estrutura para tabela michelangelo_bd.torradores
CREATE TABLE IF NOT EXISTS `torradores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `codigo_conexao` varchar(100) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_usuario` (`usuario_id`),
  CONSTRAINT `fk_torradores_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando estrutura para tabela michelangelo_bd.analise_sensorial
CREATE TABLE IF NOT EXISTS `analise_sensorial` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `torra_id` int(11) NOT NULL,
  `aroma_po` decimal(3,1) DEFAULT NULL CHECK (`aroma_po` between 0 and 10),
  `fragrancia_cafe` decimal(3,1) DEFAULT NULL CHECK (`fragrancia_cafe` between 0 and 10),
  `aroma_final` decimal(3,1) GENERATED ALWAYS AS ((`aroma_po` + `fragrancia_cafe`) / 2) STORED,
  `sabor` decimal(3,1) DEFAULT NULL CHECK (`sabor` between 0 and 10),
  `acidez` decimal(3,1) DEFAULT NULL CHECK (`acidez` between 0 and 10),
  `corpo` decimal(3,1) DEFAULT NULL CHECK (`corpo` between 0 and 10),
  `retro_gosto` decimal(3,1) DEFAULT NULL CHECK (`retro_gosto` between 0 and 10),
  `equilibrio` decimal(3,1) DEFAULT NULL CHECK (`equilibrio` between 0 and 10),
  `docura` decimal(3,1) DEFAULT NULL CHECK (`docura` between 0 and 10),
  `uniformidade` decimal(3,1) DEFAULT NULL CHECK (`uniformidade` between 0 and 10),
  `defeitos` decimal(3,1) DEFAULT NULL CHECK (`defeitos` between 0 and 10),
  `balanceamento` decimal(3,1) DEFAULT NULL CHECK (`balanceamento` between 0 and 10),
  `nota_total` decimal(5,2) GENERATED ALWAYS AS (`aroma_final` + `sabor` + `acidez` + `corpo` + `retro_gosto` + `equilibrio` + `docura` + `uniformidade` + `defeitos` + `balanceamento`) STORED,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_analise_torra` (`torra_id`),
  CONSTRAINT `fk_analise_torra` FOREIGN KEY (`torra_id`) REFERENCES `torras` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando estrutura para tabela michelangelo_bd.torras
CREATE TABLE IF NOT EXISTS `torras` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  -- Campos de qualidade do café incorporados
  `variedade` enum('Arábico','Bourbon') NOT NULL,
  `densidade` float NOT NULL,
  `fermentacao` enum('Natural','Fermentado','CD') NOT NULL,
  `finalidade` enum('Espresso','Filtro','Amostra') NOT NULL,
  -- Campos de avaliação
  `avaliada` BOOLEAN NOT NULL DEFAULT 0,
  `avaliador_id` int(11) DEFAULT NULL,
  `avaliada_em` timestamp NULL DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_usuario` (`usuario_id`),
  KEY `fk_avaliador` (`avaliador_id`),
  CONSTRAINT `fk_torras_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `fk_torras_avaliador` FOREIGN KEY (`avaliador_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
