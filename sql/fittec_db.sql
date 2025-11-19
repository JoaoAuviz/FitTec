CREATE DATABASE IF NOT EXISTS fittec_db;
USE fittec_db;

CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(120) NOT NULL,
  email VARCHAR(120) NOT NULL UNIQUE,
  senha VARCHAR(255) NOT NULL,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE treinos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome_treino VARCHAR(120) NOT NULL,
  categoria VARCHAR(60) NOT NULL,
  descricao TEXT,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE historico (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL,
  treino_id INT NOT NULL,
  exercicio VARCHAR(120) NOT NULL,
  peso DECIMAL(6,2) DEFAULT 0,
  repeticoes INT DEFAULT 0,
  desempenho ENUM('Muito Bom','Bom','Regular','Ruim','Muito Ruim') DEFAULT 'Regular',
  observacao TEXT,
  data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
  FOREIGN KEY (treino_id) REFERENCES treinos(id) ON DELETE CASCADE
);

-- dados iniciais de treinos
INSERT INTO treinos (nome_treino, categoria, descricao) VALUES
('Braço - Foco Bíceps/Tríceps','Musculação','Treino para braços: rosca, tríceps, flexões.'),
('Pernas - Força e Resistência','Musculação','Agachamentos, leg press e exercícios auxiliares.'),
('Core / Abdômen','Funcional','Prancha, abdominais e estabilidade.'),
('Cardio - Corrida & HIIT','Cardio','Corrida leve e intervalada para condicionamento.'),
('Full Body - Treino Misto','Misto','Exercícios combinados para corpo inteiro.');
