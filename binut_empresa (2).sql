-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 28/11/2024 às 15:02
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `binut_empresa`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `agendamentos`
--

CREATE TABLE `agendamentos` (
  `id_agendamento` int(11) NOT NULL,
  `id_nutri` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `horario_agendado` datetime NOT NULL,
  `status` enum('pendente','confirmado','cancelado') DEFAULT 'pendente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `agendamentos`
--

INSERT INTO `agendamentos` (`id_agendamento`, `id_nutri`, `id_cliente`, `horario_agendado`, `status`) VALUES
(6, 18, 7, '2024-11-01 12:00:00', 'pendente');

-- --------------------------------------------------------

--
-- Estrutura para tabela `cliente`
--

CREATE TABLE `cliente` (
  `id_cliente` int(11) NOT NULL,
  `nome_cliente` varchar(100) NOT NULL,
  `arroba` varchar(255) DEFAULT NULL,
  `email_cliente` varchar(100) NOT NULL,
  `senha_cliente` varchar(255) NOT NULL,
  `id_endereco` int(11) NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `foto_perfil` varchar(255) DEFAULT NULL,
  `descricao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `cliente`
--

INSERT INTO `cliente` (`id_cliente`, `nome_cliente`, `arroba`, `email_cliente`, `senha_cliente`, `id_endereco`, `data_cadastro`, `foto_perfil`, `descricao`) VALUES
(3, 'Lais', 'laiszinha', 'laiz@gmail.com', '$2y$10$EbZfEsoOvt2tRLSE086/AOCcH3UtmyGiwOhimuudgijvf4WmSpuzu', 0, '2024-11-26 21:47:37', NULL, NULL),
(4, 'mateus', 'mateus', 'mateus@gmail.com', '$2y$10$dyCZpEOuvKRkdm9r9yCpjuYF.Bc/TbVLGAk/PCZJM.wQ4EIcK6zQ6', 0, '2024-11-26 21:48:54', NULL, NULL),
(6, 'mateus', 'mateus1', 'mateus1@gmail.com', '$2y$10$DJLYdFUV0sQycBKS46yQP.SYtWHezAqlvelWf3KfgvHhcq6.u67uO', 0, '2024-11-26 21:49:55', NULL, NULL),
(7, 'sergio', 'sergiovictor', 'sergiovictor@gmail.com', '$2y$10$kkNuycp/8nFN5jEo/wX2teSKrGA9Fvv0QJ67FRsGvrTBcWtP2qaJO', 0, '2024-11-26 23:07:36', NULL, NULL);

--
-- Acionadores `cliente`
--
DELIMITER $$
CREATE TRIGGER `verificar_arroba` BEFORE INSERT ON `cliente` FOR EACH ROW BEGIN
    IF NOT NEW.arroba REGEXP '^[a-zA-ZÀ-ÿ0-9_]+$' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Nome de usúario contem caracteres especias!';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `consulta`
--

CREATE TABLE `consulta` (
  `id_consulta` int(11) NOT NULL,
  `con_desc` text DEFAULT NULL,
  `con_horario` datetime NOT NULL,
  `id_nutri` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `status_consulta` enum('Agendada','Realizada','Cancelada') DEFAULT 'Agendada',
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `preco_teleconsulta` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `disponibilidade`
--

CREATE TABLE `disponibilidade` (
  `id` int(11) NOT NULL,
  `id_nutri` int(11) DEFAULT NULL,
  `disponibilidade` text DEFAULT NULL,
  `indisponibilidade` text DEFAULT NULL,
  `con_preco` decimal(10,2) DEFAULT NULL,
  `preco_teleconsulta` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `disponibilidade`
--

INSERT INTO `disponibilidade` (`id`, `id_nutri`, `disponibilidade`, `indisponibilidade`, `con_preco`, `preco_teleconsulta`) VALUES
(1, 19, '2024-11-01 12:00, 2024-11-02 12:00, 2024-11-03 12:00, 2024-11-05 12:00, 2024-11-06 12:00, 2024-11-07 12:00, 2024-11-08 12:00, 2024-11-09 12:00', '2024-11-14 12:00, 2024-11-15 12:00, 2024-11-16 12:00, 2024-11-19 12:00, 2024-11-20 12:00, 2024-11-21 12:00, 2024-11-22 12:00, 2024-11-23 12:00, 2024-11-26 12:00, 2024-11-27 12:00', 40.00, 23.00),
(2, 18, '2024-11-01 12:00, 2024-11-02 11:30', '2024-11-27 12:00, 2024-11-28 12:00, 2024-11-29 12:00, 2024-11-30 12:00', 40.00, 23.00),
(3, 19, '2024-11-14 12:00, 2024-11-15 12:00, 2024-11-16 12:00, 2024-11-17 12:00', '2024-11-18 12:00, 2024-11-19 12:00, 2024-11-20 12:00, 2024-11-21 12:00, 2024-11-22 12:00', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `endereco`
--

CREATE TABLE `endereco` (
  `id_endereco` int(11) NOT NULL,
  `rua` varchar(100) NOT NULL,
  `numero` varchar(10) NOT NULL,
  `complemento` varchar(50) DEFAULT NULL,
  `cidade` varchar(50) NOT NULL,
  `bairro` varchar(50) NOT NULL,
  `CEP` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Acionadores `endereco`
--
DELIMITER $$
CREATE TRIGGER `verificar_cep` BEFORE INSERT ON `endereco` FOR EACH ROW BEGIN 
    IF LENGTH(NEW.CEP) != 8 THEN 
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'O CEP deve ter exatamente 8 caracteres.'; 
    END IF; 
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `especialidade`
--

CREATE TABLE `especialidade` (
  `id_especialidade` bigint(20) UNSIGNED NOT NULL,
  `descricao` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `nutricionista`
--

CREATE TABLE `nutricionista` (
  `id_nutri` int(11) NOT NULL,
  `nome_nutri` varchar(100) NOT NULL,
  `arroba_nutri` varchar(255) DEFAULT NULL,
  `email_nutri` varchar(225) NOT NULL,
  `especialidade` varchar(255) DEFAULT NULL,
  `CRN_nutri` varchar(4) NOT NULL,
  `senha_nutri` varchar(255) NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_endereco` int(11) DEFAULT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL,
  `descricao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `nutricionista`
--

INSERT INTO `nutricionista` (`id_nutri`, `nome_nutri`, `arroba_nutri`, `email_nutri`, `especialidade`, `CRN_nutri`, `senha_nutri`, `data_cadastro`, `id_endereco`, `foto_perfil`, `descricao`) VALUES
(16, 'Maria Silva', 'mariazinha', 'mariasilva@gmail.com', 'Nutrição em saúde mental', '1112', '$2y$10$uVrd39S49plvRGJX.AAqC.GM/w2vG5Op2TJ0cqlvO4B5qh87Nx8iO', '2024-11-26 21:54:18', NULL, NULL, NULL),
(17, 'Janne', 'janezinha', 'janne@gmail.com', 'Nutrição clínica em nefrologia', '1222', '$2y$10$hTxi3HV8PZhxtCZpRBvA0eat98nqIZXCMdkaH7LiiIYyntnnoWvBS', '2024-11-26 23:39:32', NULL, NULL, NULL),
(18, 'Ana', 'ana', 'ana@gmail.com', 'Educação alimentar e nutricional', '3333', '$2y$10$K8O8.WsRKoUf8pRLNAhWM.K21BK4sCktxjajs973s4va4BR.5m04q', '2024-11-26 23:41:08', NULL, NULL, NULL),
(19, 'marcusaba', 'marcusaba', 'marcus1@gmail.com', 'Nutrição em estética', '2222', '$2y$10$A5RVyAbXEiDllnMuXBXqqO/yaEwESMEoKRfB8fLv9XBYxr1T5/lAK', '2024-11-26 23:52:45', NULL, NULL, NULL);

--
-- Acionadores `nutricionista`
--
DELIMITER $$
CREATE TRIGGER `verificar_arroba_nutri` BEFORE INSERT ON `nutricionista` FOR EACH ROW BEGIN
    IF NOT NEW.arroba_nutri REGEXP '^[a-zA-ZÀ-ÿ0-9_]+$' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Nome de usuário inválido!';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `nutricionista_especialidade`
--

CREATE TABLE `nutricionista_especialidade` (
  `id_nutri` int(11) NOT NULL,
  `id_especialidade` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `opiniao_nutricionista`
--

CREATE TABLE `opiniao_nutricionista` (
  `id` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_nutri` int(11) NOT NULL,
  `opiniao` text DEFAULT NULL,
  `data_postagem` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `opiniao_nutricionista`
--

INSERT INTO `opiniao_nutricionista` (`id`, `id_cliente`, `id_nutri`, `opiniao`, `data_postagem`) VALUES
(1, 3, 16, 'Adorei a consulta, muito compreensiva a profissional', '2024-11-26 20:24:50'),
(2, 7, 18, 'Adorei a consulta! Muito bom!', '2024-11-28 10:31:59');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pagamento`
--

CREATE TABLE `pagamento` (
  `id_pagamento` int(11) NOT NULL,
  `id_consulta` int(11) NOT NULL,
  `id_nutri` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `horario_pag` datetime NOT NULL DEFAULT current_timestamp(),
  `valor_pago` decimal(10,2) NOT NULL,
  `status_pagamento` enum('Pendente','Pago','Cancelado') DEFAULT 'Pendente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `agendamentos`
--
ALTER TABLE `agendamentos`
  ADD PRIMARY KEY (`id_agendamento`),
  ADD KEY `id_nutri` (`id_nutri`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Índices de tabela `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id_cliente`),
  ADD UNIQUE KEY `uk_email_cliente` (`email_cliente`),
  ADD UNIQUE KEY `unique_arroba` (`arroba`),
  ADD UNIQUE KEY `arroba` (`arroba`);

--
-- Índices de tabela `consulta`
--
ALTER TABLE `consulta`
  ADD PRIMARY KEY (`id_consulta`),
  ADD KEY `id_nutri` (`id_nutri`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Índices de tabela `disponibilidade`
--
ALTER TABLE `disponibilidade`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_nutri` (`id_nutri`);

--
-- Índices de tabela `endereco`
--
ALTER TABLE `endereco`
  ADD PRIMARY KEY (`id_endereco`);

--
-- Índices de tabela `especialidade`
--
ALTER TABLE `especialidade`
  ADD PRIMARY KEY (`id_especialidade`);

--
-- Índices de tabela `nutricionista`
--
ALTER TABLE `nutricionista`
  ADD PRIMARY KEY (`id_nutri`),
  ADD UNIQUE KEY `uk_email_nutri` (`email_nutri`),
  ADD UNIQUE KEY `uk_crn` (`CRN_nutri`),
  ADD UNIQUE KEY `unique_arroba_nutri` (`arroba_nutri`),
  ADD KEY `id_endereco` (`id_endereco`);

--
-- Índices de tabela `nutricionista_especialidade`
--
ALTER TABLE `nutricionista_especialidade`
  ADD PRIMARY KEY (`id_nutri`,`id_especialidade`),
  ADD KEY `id_especialidade` (`id_especialidade`);

--
-- Índices de tabela `opiniao_nutricionista`
--
ALTER TABLE `opiniao_nutricionista`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_nutri` (`id_nutri`);

--
-- Índices de tabela `pagamento`
--
ALTER TABLE `pagamento`
  ADD PRIMARY KEY (`id_pagamento`),
  ADD KEY `id_consulta` (`id_consulta`),
  ADD KEY `id_nutri` (`id_nutri`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `agendamentos`
--
ALTER TABLE `agendamentos`
  MODIFY `id_agendamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `consulta`
--
ALTER TABLE `consulta`
  MODIFY `id_consulta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `disponibilidade`
--
ALTER TABLE `disponibilidade`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `endereco`
--
ALTER TABLE `endereco`
  MODIFY `id_endereco` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `especialidade`
--
ALTER TABLE `especialidade`
  MODIFY `id_especialidade` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `nutricionista`
--
ALTER TABLE `nutricionista`
  MODIFY `id_nutri` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de tabela `opiniao_nutricionista`
--
ALTER TABLE `opiniao_nutricionista`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `pagamento`
--
ALTER TABLE `pagamento`
  MODIFY `id_pagamento` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `agendamentos`
--
ALTER TABLE `agendamentos`
  ADD CONSTRAINT `agendamentos_ibfk_1` FOREIGN KEY (`id_nutri`) REFERENCES `nutricionista` (`id_nutri`),
  ADD CONSTRAINT `agendamentos_ibfk_2` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`);

--
-- Restrições para tabelas `consulta`
--
ALTER TABLE `consulta`
  ADD CONSTRAINT `consulta_ibfk_1` FOREIGN KEY (`id_nutri`) REFERENCES `nutricionista` (`id_nutri`),
  ADD CONSTRAINT `consulta_ibfk_2` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`);

--
-- Restrições para tabelas `disponibilidade`
--
ALTER TABLE `disponibilidade`
  ADD CONSTRAINT `disponibilidade_ibfk_1` FOREIGN KEY (`id_nutri`) REFERENCES `nutricionista` (`id_nutri`) ON DELETE CASCADE;

--
-- Restrições para tabelas `nutricionista_especialidade`
--
ALTER TABLE `nutricionista_especialidade`
  ADD CONSTRAINT `nutricionista_especialidade_ibfk_1` FOREIGN KEY (`id_nutri`) REFERENCES `nutricionista` (`id_nutri`),
  ADD CONSTRAINT `nutricionista_especialidade_ibfk_2` FOREIGN KEY (`id_especialidade`) REFERENCES `especialidade` (`id_especialidade`);

--
-- Restrições para tabelas `opiniao_nutricionista`
--
ALTER TABLE `opiniao_nutricionista`
  ADD CONSTRAINT `opiniao_nutricionista_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`),
  ADD CONSTRAINT `opiniao_nutricionista_ibfk_2` FOREIGN KEY (`id_nutri`) REFERENCES `nutricionista` (`id_nutri`);

--
-- Restrições para tabelas `pagamento`
--
ALTER TABLE `pagamento`
  ADD CONSTRAINT `pagamento_ibfk_1` FOREIGN KEY (`id_consulta`) REFERENCES `consulta` (`id_consulta`),
  ADD CONSTRAINT `pagamento_ibfk_2` FOREIGN KEY (`id_nutri`) REFERENCES `nutricionista` (`id_nutri`),
  ADD CONSTRAINT `pagamento_ibfk_3` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
