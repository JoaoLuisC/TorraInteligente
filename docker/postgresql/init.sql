-- PostgreSQL init script for Torra Inteligente
-- Compatible with PostgreSQL 16

-- Create database (if running locally, uncomment next line)
-- CREATE DATABASE michelangelo_bd;

-- Connect to database (if running locally, uncomment next line)
-- \c michelangelo_bd;

-- Create usuarios table
CREATE TABLE IF NOT EXISTS usuarios (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    sobrenome VARCHAR(100) NOT NULL,
    tipo VARCHAR(20) CHECK (tipo IN ('Analista', 'Produtor', 'Administrador')) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create torradores table
CREATE TABLE IF NOT EXISTS torradores (
    id SERIAL PRIMARY KEY,
    usuario_id INTEGER NOT NULL,
    nome VARCHAR(100) NOT NULL,
    codigo_conexao VARCHAR(100) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_torradores_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- Create torras table
CREATE TABLE IF NOT EXISTS torras (
    id SERIAL PRIMARY KEY,
    usuario_id INTEGER NOT NULL,
    nome VARCHAR(100) NOT NULL,
    variedade VARCHAR(20) CHECK (variedade IN ('Arábico', 'Bourbon')) NOT NULL,
    densidade FLOAT NOT NULL,
    fermentacao VARCHAR(20) CHECK (fermentacao IN ('Natural', 'Fermentado', 'CD')) NOT NULL,
    finalidade VARCHAR(20) CHECK (finalidade IN ('Espresso', 'Filtro', 'Amostra')) NOT NULL,
    avaliada BOOLEAN DEFAULT FALSE,
    avaliador_id INTEGER DEFAULT NULL,
    avaliada_em TIMESTAMP DEFAULT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_torras_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    CONSTRAINT fk_torras_avaliador FOREIGN KEY (avaliador_id) REFERENCES usuarios(id)
);

-- Create analise_sensorial table
CREATE TABLE IF NOT EXISTS analise_sensorial (
    id SERIAL PRIMARY KEY,
    torra_id INTEGER NOT NULL,
    aroma_po DECIMAL(3,1) CHECK (aroma_po BETWEEN 0 AND 10),
    fragrancia_cafe DECIMAL(3,1) CHECK (fragrancia_cafe BETWEEN 0 AND 10),
    aroma_final DECIMAL(3,1) GENERATED ALWAYS AS ((aroma_po + fragrancia_cafe) / 2) STORED,
    sabor DECIMAL(3,1) CHECK (sabor BETWEEN 0 AND 10),
    acidez DECIMAL(3,1) CHECK (acidez BETWEEN 0 AND 10),
    corpo DECIMAL(3,1) CHECK (corpo BETWEEN 0 AND 10),
    retro_gosto DECIMAL(3,1) CHECK (retro_gosto BETWEEN 0 AND 10),
    equilibrio DECIMAL(3,1) CHECK (equilibrio BETWEEN 0 AND 10),
    docura DECIMAL(3,1) CHECK (docura BETWEEN 0 AND 10),
    uniformidade DECIMAL(3,1) CHECK (uniformidade BETWEEN 0 AND 10),
    defeitos DECIMAL(3,1) CHECK (defeitos BETWEEN 0 AND 10),
    balanceamento DECIMAL(3,1) CHECK (balanceamento BETWEEN 0 AND 10),
    nota_total DECIMAL(5,2) GENERATED ALWAYS AS (
        ((aroma_po + fragrancia_cafe) / 2) + sabor + acidez + corpo + retro_gosto +
        equilibrio + docura + uniformidade + defeitos + balanceamento
    ) STORED,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_analise_torra FOREIGN KEY (torra_id) REFERENCES torras(id)
);

-- Insert sample data (same as MySQL version)
INSERT INTO usuarios (nome, sobrenome, tipo, email, senha, criado_em) VALUES
    ('joao', 'cardoso', 'Administrador', 'carlos@gmail', '$2y$12$RRpMC9JaT7sxlFNDhSA5leNd7FQlIsrbWvo.B51eDnmm9AJdj1XLy', '2025-06-17 12:18:33'),
    ('joao', 'cardoso', 'Analista', 'dunthone2016@gmail.com', '$2y$12$G7ep4iMybO23V15SDG4//.xDLdKdZnwA1PzE/fvTFqXl0uJpeUIeG', '2025-06-17 12:27:26'),
    ('carlos', 'guedes', 'Analista', 'carlao@gmail.com', '$2y$12$tstUPTb2klBxjrpt1lKYF.lW2LwV86PonBdU7jvySbpFuyLrJYf8a', '2025-06-17 23:53:10')
ON CONFLICT (email) DO NOTHING;
