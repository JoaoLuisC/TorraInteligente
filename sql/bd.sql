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

-- Copiando estrutura para tabela michelangelo_bd.analise_sensorial
CREATE TABLE IF NOT EXISTS `analise_sensorial` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cafe_id` int(11) NOT NULL,
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
  KEY `fk_analise_qualidade` (`cafe_id`),
  CONSTRAINT `analise_sensorial_ibfk_1` FOREIGN KEY (`cafe_id`) REFERENCES `qualidade_cafe` (`id`),
  CONSTRAINT `fk_analise_qualidade` FOREIGN KEY (`cafe_id`) REFERENCES `qualidade_cafe` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela michelangelo_bd.qualidade_cafe
CREATE TABLE IF NOT EXISTS `qualidade_cafe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `variedade` enum('Arábico','Bourbon') NOT NULL,
  `densidade` float NOT NULL,
  `fermentacao` enum('Natural','Fermentado','CD') NOT NULL,
  `finalidade` enum('Espresso','Filtro','Amostra') NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `usuario_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_qualidade_usuario` (`usuario_id`),
  CONSTRAINT `fk_qualidade_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela michelangelo_bd.torradores
CREATE TABLE IF NOT EXISTS `torradores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_usuario` (`usuario_id`),
  CONSTRAINT `fk_torradores_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela michelangelo_bd.torras
CREATE TABLE IF NOT EXISTS `torras` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `qualidade_cafe_id` int(11) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_usuario` (`usuario_id`),
  KEY `fk_qualidade` (`qualidade_cafe_id`),
  CONSTRAINT `fk_torras_qualidade` FOREIGN KEY (`qualidade_cafe_id`) REFERENCES `qualidade_cafe` (`id`),
  CONSTRAINT `fk_torras_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela michelangelo_bd.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `sobrenome` varchar(100) NOT NULL,
  `funcao_usuario` enum('Administrador','Produtor','Avaliador') NOT NULL,
  `email` varchar(150) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Exportação de dados foi desmarcado.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
