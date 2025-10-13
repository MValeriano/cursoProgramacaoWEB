<?php
// config.php - Configurações do banco de dados SQLite
define('DB_PATH', __DIR__ . '/feirinha_virtual.sqlite');
define('DB_TIMEOUT', 5000); // 5 segundos de timeout

// Função para conectar ao banco SQLite com tratamento de lock
function conectarBanco() {
    try {
        // Verificar se o diretório existe, se não, criar
        $dbDir = dirname(DB_PATH);
        if (!file_exists($dbDir)) {
            mkdir($dbDir, 0755, true);
        }
        
        $pdo = new PDO('sqlite:' . DB_PATH, null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 5, // Timeout de 5 segundos
            PDO::ATTR_PERSISTENT => false // Não usar conexões persistentes
        ]);
        
        // Configurar modo de locking do SQLite para melhor performance
        $pdo->exec('PRAGMA journal_mode = WAL;'); // Write-Ahead Logging
        $pdo->exec('PRAGMA synchronous = NORMAL;');
        $pdo->exec('PRAGMA busy_timeout = ' . DB_TIMEOUT . ';');
        
        // Criar tabelas se não existirem
        inicializarBanco($pdo);
        
        return $pdo;
    } catch (PDOException $e) {
        // Tentar reconectar em caso de erro de lock
        if (strpos($e->getMessage(), 'database is locked') !== false) {
            usleep(100000); // Esperar 100ms
            try {
                $pdo = new PDO('sqlite:' . DB_PATH);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $pdo;
            } catch (PDOException $e2) {
                // Se ainda falhar, mostrar erro amigável
                erroBanco("O banco de dados está temporariamente indisponível. Por favor, tente novamente em alguns instantes.");
            }
        } else {
            erroBanco("Erro de conexão com o banco de dados: " . $e->getMessage());
        }
    }
}

// Função para mostrar erro de banco de dados
function erroBanco($mensagem) {
    // Log do erro (em produção)
    error_log($mensagem);
    
    // Mensagem amigável para o usuário
    if (php_sapi_name() !== 'cli') {
        echo "<!DOCTYPE html>
        <html>
        <head>
            <title>Erro no Sistema</title>
            <style>
                body { font-family: Arial, sans-serif; padding: 20px; text-align: center; }
                .erro { background: #ffebee; border: 1px solid #f44336; padding: 20px; border-radius: 5px; margin: 20px auto; max-width: 500px; }
            </style>
        </head>
        <body>
            <div class='erro'>
                <h2>😕 Ocorreu um erro</h2>
                <p>$mensagem</p>
                <p><small>Tente recarregar a página ou voltar mais tarde.</small></p>
            </div>
        </body>
        </html>";
    } else {
        echo $mensagem . "\n";
    }
    exit;
}

// Função para inicializar o banco de dados
function inicializarBanco($pdo) {
    // Criar tabela de usuários
    $pdo->exec("CREATE TABLE IF NOT EXISTS usuarios (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nome TEXT NOT NULL,
        email TEXT UNIQUE NOT NULL,
        telefone TEXT,
        senha TEXT NOT NULL,
        bairro TEXT,
        data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP,
        ativo INTEGER DEFAULT 1
    )");
    
    // Criar tabela de categorias
    $pdo->exec("CREATE TABLE IF NOT EXISTS categorias (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nome TEXT NOT NULL,
        descricao TEXT,
        icone TEXT
    )");
    
    // Inserir categorias padrão se a tabela estiver vazia
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM categorias");
        $result = $stmt->fetch();
        
        if ($result['count'] == 0) {
            $categorias = [
                ['Artesanato', 'Produtos artesanais feitos à mão', 'fas fa-palette'],
                ['Culinária', 'Comidas, bolos, doces e salgados', 'fas fa-utensils'],
                ['Costura', 'Roupas, ajustes e consertos', 'fas fa-cut'],
                ['Serviços', 'Diversos serviços oferecidos', 'fas fa-concierge-bell'],
                ['Outros', 'Outros tipos de produtos ou serviços', 'fas fa-ellipsis-h']
            ];
            
            $insert = $pdo->prepare("INSERT INTO categorias (nome, descricao, icone) VALUES (?, ?, ?)");
            
            foreach ($categorias as $categoria) {
                $insert->execute($categoria);
            }
        }
    } catch (PDOException $e) {
        // Ignorar erros de inserção de categorias (podem já existir)
    }
    
    // Criar tabela de produtos
    $pdo->exec("CREATE TABLE IF NOT EXISTS produtos (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        usuario_id INTEGER,
        categoria_id INTEGER,
        nome TEXT NOT NULL,
        descricao TEXT NOT NULL,
        preco TEXT,
        contato TEXT NOT NULL,
        imagem TEXT,
        data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP,
        ativo INTEGER DEFAULT 1,
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
        FOREIGN KEY (categoria_id) REFERENCES categorias(id)
    )");
    
    // Criar tabela de favoritos (opcional)
    $pdo->exec("CREATE TABLE IF NOT EXISTS favoritos (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        usuario_id INTEGER,
        produto_id INTEGER,
        data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
        FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE,
        UNIQUE (usuario_id, produto_id)
    )");
}

// Função para buscar todos os produtos com tratamento de lock
function buscarProdutos($categoria = null, $busca = null) {
    $tentativas = 0;
    $max_tentativas = 3;
    
    while ($tentativas < $max_tentativas) {
        try {
            $pdo = conectarBanco();
            
            $sql = "SELECT p.*, c.nome as categoria_nome 
                    FROM produtos p 
                    INNER JOIN categorias c ON p.categoria_id = c.id 
                    WHERE p.ativo = 1";
            
            $params = [];
            
            if ($categoria) {
                $sql .= " AND c.nome = ?";
                $params[] = $categoria;
            }
            
            if ($busca) {
                $sql .= " AND (p.nome LIKE ? OR p.descricao LIKE ?)";
                $params[] = "%$busca%";
                $params[] = "%$busca%";
            }
            
            $sql .= " ORDER BY p.data_cadastro DESC";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            $tentativas++;
            if ($tentativas >= $max_tentativas || strpos($e->getMessage(), 'database is locked') === false) {
                // Se não for erro de lock ou já tentou demais, retornar array vazio
                error_log("Erro ao buscar produtos: " . $e->getMessage());
                return [];
            }
            usleep(100000 * $tentativas); // Esperar progressivamente mais
        }
    }
    
    return [];
}

// Função para cadastrar novo produto com tratamento de lock
function cadastrarProduto($dados) {
    $tentativas = 0;
    $max_tentativas = 3;
    
    while ($tentativas < $max_tentativas) {
        try {
            $pdo = conectarBanco();

            $sql = "INSERT INTO produtos (usuario_id, categoria_id, nome, descricao, preco, contato, imagem) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";

            $stmt = $pdo->prepare($sql);
            
            return $stmt->execute([
                $dados['usuario_id'],
                $dados['categoria_id'],
                $dados['nome'],
                $dados['descricao'],
                $dados['preco'],
                $dados['contato'],
                $dados['imagem']
            ]);
        } catch (PDOException $e) {
            $tentativas++;
            if ($tentativas >= $max_tentativas || strpos($e->getMessage(), 'database is locked') === false) {
                error_log("Erro ao cadastrar produto: " . $e->getMessage());
                return false;
            }
            usleep(100000 * $tentativas); // Esperar progressivamente mais
        }
    }
    
    return false;
}

// Função para buscar categorias com tratamento de lock
function buscarCategorias() {
    $tentativas = 0;
    $max_tentativas = 3;
    
    while ($tentativas < $max_tentativas) {
        try {
            $pdo = conectarBanco();
            
            $sql = "SELECT * FROM categorias ORDER BY nome";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            $tentativas++;
            if ($tentativas >= $max_tentativas || strpos($e->getMessage(), 'database is locked') === false) {
                error_log("Erro ao buscar categorias: " . $e->getMessage());
                return [];
            }
            usleep(100000 * $tentativas);
        }
    }
    
    return [];
}

// Função para cadastrar usuário com tratamento de lock
function cadastrarUsuario($dados) {
    $tentativas = 0;
    $max_tentativas = 3;
    
    while ($tentativas < $max_tentativas) {
        try {
            $pdo = conectarBanco();
            
            // Verificar se email já existe
            $sql = "SELECT id FROM usuarios WHERE email = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$dados['email']]);
            
            if ($stmt->fetch()) {
                return false; // Email já cadastrado
            }
            
            // Hash da senha
            $senhaHash = password_hash($dados['senha'], PASSWORD_DEFAULT);
            
            $sql = "INSERT INTO usuarios (nome, email, telefone, senha, bairro) 
                    VALUES (?, ?, ?, ?, ?)";
            
            $stmt = $pdo->prepare($sql);
            
            return $stmt->execute([
                $dados['nome'],
                $dados['email'],
                $dados['telefone'],
                $senhaHash,
                $dados['bairro']
            ]);
        } catch (PDOException $e) {
            $tentativas++;
            if ($tentativas >= $max_tentativas || strpos($e->getMessage(), 'database is locked') === false) {
                error_log("Erro ao cadastrar usuário: " . $e->getMessage());
                return false;
            }
            usleep(100000 * $tentativas);
        }
    }
    
    return false;
}

// Função para autenticar usuário com tratamento de lock
function autenticarUsuario($email, $senha) {
    $tentativas = 0;
    $max_tentativas = 3;
    
    while ($tentativas < $max_tentativas) {
        try {
            $pdo = conectarBanco();
            
            $sql = "SELECT * FROM usuarios WHERE email = ? AND ativo = 1";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$email]);
            
            $usuario = $stmt->fetch();
            
            if ($usuario && password_verify($senha, $usuario['senha'])) {
                return $usuario;
            }
            
            return false;
        } catch (PDOException $e) {
            $tentativas++;
            if ($tentativas >= $max_tentativas || strpos($e->getMessage(), 'database is locked') === false) {
                error_log("Erro ao autenticar usuário: " . $e->getMessage());
                return false;
            }
            usleep(100000 * $tentativas);
        }
    }
    
    return false;
}
?>