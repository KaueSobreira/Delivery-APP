CREATE TABLE produtos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome_produto VARCHAR(50),
    preco FLOAT,
    descricao VARCHAR(150),
    categoria_id INT,
    nome_original VARCHAR(100),
    nome_imagem VARCHAR(100),
    caminho_imagem VARCHAR(100),
    FOREIGN KEY (categoria_id) REFERENCES categoria(id)
);

CREATE TABLE categoria (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome_categoria VARCHAR(50)
);