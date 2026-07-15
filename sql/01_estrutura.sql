-- =====================================================================
--  SISTEMA DE RESERVAS E EMPRÉSTIMOS - ESTRUTURA DO BANCO DE DADOS
--  Passo 1: abra o phpMyAdmin (http://localhost/phpmyadmin)
--  Passo 2: aba "SQL", cole ESTE arquivo inteiro e clique em "Executar"
--  Passo 3: depois faça o mesmo com o 02_dados_iniciais.sql
-- =====================================================================

CREATE DATABASE IF NOT EXISTS reservas_db
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE reservas_db;

-- Se quiser recomeçar do zero, descomente as 5 linhas abaixo:
-- DROP TABLE IF EXISTS reservas;
-- DROP TABLE IF EXISTS recursos;
-- DROP TABLE IF EXISTS categorias;
-- DROP TABLE IF EXISTS setores;
-- DROP TABLE IF EXISTS usuarios;

-- ---------------------------------------------------------------------
-- Tabela: usuarios  (login web e mobile)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS usuarios (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  nome        VARCHAR(100)  NOT NULL,
  email       VARCHAR(150)  NOT NULL UNIQUE,
  senha       VARCHAR(255)  NOT NULL,               -- guardada com password_hash()
  tipo        ENUM('admin','user') NOT NULL DEFAULT 'user',
  criado_em   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- Tabela auxiliar: categorias  (Módulo 1)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS categorias (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  nome        VARCHAR(80)  NOT NULL,
  descricao   VARCHAR(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- Tabela auxiliar: setores  (Módulo 2)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS setores (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  nome        VARCHAR(80)  NOT NULL,
  responsavel VARCHAR(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- Tabela principal: recursos  (Módulo 3 - o Core)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS recursos (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  nome         VARCHAR(120) NOT NULL,
  descricao    TEXT DEFAULT NULL,
  foto         VARCHAR(255) DEFAULT NULL,           -- nome do arquivo salvo em /uploads
  categoria_id INT NOT NULL,
  setor_id     INT NOT NULL,
  ativo        TINYINT(1) NOT NULL DEFAULT 1,
  criado_em    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_recurso_categoria FOREIGN KEY (categoria_id)
      REFERENCES categorias(id) ON DELETE RESTRICT,
  CONSTRAINT fk_recurso_setor FOREIGN KEY (setor_id)
      REFERENCES setores(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- Tabela: reservas  (registra reservas do WEB e do MOBILE)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS reservas (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  recurso_id   INT NOT NULL,
  usuario_id   INT NOT NULL,
  data_reserva DATE NOT NULL,
  turno        ENUM('manha','tarde','noite') NOT NULL,
  origem       ENUM('web','mobile') NOT NULL DEFAULT 'web',
  status       ENUM('ativa','cancelada') NOT NULL DEFAULT 'ativa',
  criado_em    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_reserva_recurso FOREIGN KEY (recurso_id)
      REFERENCES recursos(id) ON DELETE CASCADE,
  CONSTRAINT fk_reserva_usuario FOREIGN KEY (usuario_id)
      REFERENCES usuarios(id) ON DELETE CASCADE,
  -- impede duas reservas do mesmo recurso no mesmo dia/turno:
  CONSTRAINT uq_recurso_data_turno UNIQUE (recurso_id, data_reserva, turno)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
