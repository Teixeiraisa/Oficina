CREATE DATABASE IF NOT EXISTS oficina
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE oficina;

-- Cadastro de veículos da oficina
CREATE TABLE IF NOT EXISTS veiculos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  cliente VARCHAR(100) NOT NULL,
  placa VARCHAR(20) NOT NULL,
  modelo VARCHAR(100) NOT NULL,
  problema TEXT NOT NULL,
  status VARCHAR(30) NOT NULL DEFAULT 'Aguardando',
  prioridade VARCHAR(20) NOT NULL DEFAULT 'Normal',
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Cadastro de peças que chegaram na loja
CREATE TABLE IF NOT EXISTS pecas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(120) NOT NULL,
  marca VARCHAR(100) NOT NULL,
  categoria VARCHAR(80) NOT NULL,
  quantidade INT NOT NULL,
  custo_unitario DECIMAL(10,2) NOT NULL,
  fornecedor VARCHAR(120) NOT NULL,
  chegada_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Movimentações financeiras
CREATE TABLE IF NOT EXISTS movimentacoes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  tipo ENUM('receita','despesa') NOT NULL,
  descricao VARCHAR(150) NOT NULL,
  categoria VARCHAR(80) NOT NULL,
  valor DECIMAL(10,2) NOT NULL,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-- Tabela de usuários da gerência
CREATE TABLE IF NOT EXISTS gerencia (
id INT AUTO_INCREMENT PRIMARY KEY,
usuario VARCHAR(100) NOT NULL UNIQUE,
senha VARCHAR(255) NOT NULL,
criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Histórico de entradas e saídas
CREATE TABLE entradaesaida (
    id INT AUTO_INCREMENT PRIMARY KEY,
    peca_id INT,
    tipo ENUM('entrada','saida'),
    quantidade INT,
    data_movimentacao DATETIME DEFAULT CURRENT_TIMESTAMP
);


