<?php
require_once 'config.php';

// Função para obter ou criar usuário padrão
function obterUsuarioPadrao() {
    $tentativas = 0;
    $max_tentativas = 3;
    
    while ($tentativas < $max_tentativas) {
        try {
            $pdo = conectarBanco();
            
            // Verificar se existe algum usuário, se não, criar um padrão
            $stmt = $pdo->query("SELECT id FROM usuarios LIMIT 1");
            $usuario = $stmt->fetch();
            
            if (!$usuario) {
                // Criar um usuário padrão
                $senhaHash = password_hash('senha123', PASSWORD_DEFAULT);
                $insert = $pdo->prepare("INSERT INTO usuarios (nome, email, telefone, senha, bairro) VALUES (?, ?, ?, ?, ?)");
                $insert->execute(['Usuária Padrão', 'default@email.com', '(11) 99999-9999', $senhaHash, 'Centro']);
                
                return $pdo->lastInsertId();
            } else {
                return $usuario['id'];
            }
        } catch (PDOException $e) {
            $tentativas++;
            if ($tentativas >= $max_tentativas || strpos($e->getMessage(), 'database is locked') === false) {
                error_log("Erro ao obter usuário padrão: " . $e->getMessage());
                return 1; // Valor padrão de fallback
            }
            usleep(100000 * $tentativas);
        }
    }
    
    return 1; // Valor padrão de fallback
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coletar dados do formulário
    $dados = [
        'usuario_id' => obterUsuarioPadrao(),
        'categoria_id' => $_POST['categoria_id'],
        'nome' => $_POST['nome'],
        'descricao' => $_POST['descricao'],
        'preco' => $_POST['preco'] ?: 'A combinar',
        'contato' => $_POST['contato'],
        'imagem' => $_POST['imagem'] ?: null
    ];
    
    // Cadastrar o produto
    if (cadastrarProduto($dados)) {
        header('Location: index.php?sucesso=1');
        exit;
    } else {
        header('Location: index.php?erro=1');
        exit;
    }
} else {
    header('Location: index.php');
    exit;
}
?>