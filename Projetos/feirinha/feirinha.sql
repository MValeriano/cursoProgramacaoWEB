-- Criar tabela de usuários
CREATE TABLE IF NOT EXISTS usuarios (
    id SERIAL PRIMARY KEY,
    nome VARCHAR NOT NULL,
    email VARCHAR UNIQUE NOT NULL,
    telefone VARCHAR,
    senha VARCHAR NOT NULL,
    bairro VARCHAR,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ativo INTEGER DEFAULT 1
);

-- Criar tabela de categorias
CREATE TABLE IF NOT EXISTS categorias (
    id SERIAL PRIMARY KEY,
    nome VARCHAR NOT NULL,
    descricao TEXT,
    icone VARCHAR
);

-- Criar tabela de produtos
CREATE TABLE IF NOT EXISTS produtos (
    id SERIAL PRIMARY KEY,
    usuario_id INTEGER,
    categoria_id INTEGER,
    nome VARCHAR NOT NULL,
    descricao TEXT NOT NULL,
    preco VARCHAR,
    contato VARCHAR NOT NULL,
    imagem VARCHAR,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ativo INTEGER DEFAULT 1,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id)
);

-- Criar tabela de favoritos
CREATE TABLE IF NOT EXISTS favoritos (
    id SERIAL PRIMARY KEY,
    usuario_id INTEGER,
    produto_id INTEGER,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE,
    UNIQUE (usuario_id, produto_id)
);