CREATE TABLE produtos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome_produto VARCHAR(50),
    preco FLOAT,
    descricao VARCHAR(150),
    categoria_id INT,
    nome_original VARCHAR(100),
    nome_imagem VARCHAR(100),
    caminho_imagem VARCHAR(100),
    em_promocao BOOLEAN DEFAULT 0,
    FOREIGN KEY (categoria_id) REFERENCES categoria(id)
);

CREATE TABLE categoria (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome_categoria VARCHAR(50)
);

CREATE TABLE configuracoes_empresa (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome_empresa VARCHAR(100) NOT NULL DEFAULT 'SPACE BURGER',
    sobre_empresa TEXT,
    titulo_banner VARCHAR(200) DEFAULT 'UM UNIVERSO DE SABORES',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE horarios_funcionamento (
    id INT PRIMARY KEY AUTO_INCREMENT,
    dia_semana TINYINT NOT NULL,
    horario_inicio TIME,
    horario_fim TIME,
    aberto BOOLEAN DEFAULT TRUE,
    empresa_id INT DEFAULT 1,
    FOREIGN KEY (empresa_id) REFERENCES configuracoes_empresa(id) ON DELETE CASCADE
);