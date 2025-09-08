<?php
require_once 'config.php';

// Verificar parâmetros de sucesso/erro
$mensagem = '';
$tipoMensagem = '';

if (isset($_GET['sucesso']) && $_GET['sucesso'] == 1) {
    $mensagem = 'Produto cadastrado com sucesso!';
    $tipoMensagem = 'sucesso';
} elseif (isset($_GET['erro']) && $_GET['erro'] == 1) {
    $mensagem = 'Erro ao cadastrar produto. Tente novamente.';
    $tipoMensagem = 'erro';
}

// Verificar filtros
$categoriaFiltro = isset($_GET['categoria']) ? $_GET['categoria'] : null;
$buscaFiltro = isset($_GET['busca']) ? $_GET['busca'] : null;

// Carregar produtos
$produtos = buscarProdutos($categoriaFiltro, $buscaFiltro);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feirinha Virtual - Talentos Locais</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #8A2BE2;
            --primary-light: #9b45f0;
            --secondary: #FF4081;
            --dark: #2D2B35;
            --light: #F8F9FA;
            --gray: #6c757d;
            --success: #28a745;
        }
		
/* Mensagens de status */
.mensagem-status {
    padding: 15px;
    margin: 20px auto;
    max-width: 600px;
    border-radius: 5px;
    text-align: center;
}

.mensagem-sucesso {
    background: #e8f5e9;
    border: 1px solid #4caf50;
    color: #2e7d32;
}

.mensagem-erro {
    background: #ffebee;
    border: 1px solid #f44336;
    color: #c62828;
}		
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--light);
            color: #333;
            line-height: 1.6;
        }
        
        h1, h2, h3, h4, h5 {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            color: var(--dark);
        }
        
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Header */
        header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 15px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-size: 24px;
            font-weight: 700;
            display: flex;
            align-items: center;
        }
        
        .logo i {
            margin-right: 10px;
        }
        
        .nav-links {
            display: flex;
            list-style: none;
        }
        
        .nav-links li {
            margin-left: 30px;
        }
        
        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: opacity 0.3s;
        }
        
        .nav-links a:hover {
            opacity: 0.8;
        }
        
        .btn {
            display: inline-block;
            background-color: var(--secondary);
            color: white;
            padding: 10px 20px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
        }
        
        .btn:hover {
            background-color: var(--primary);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(142, 45, 226, 0.4);
        }
        
        .btn-outline {
            background: transparent;
            border: 2px solid var(--primary);
            color: var(--primary);
        }
        
        .btn-outline:hover {
            background: var(--primary);
            color: white;
        }
        
        /* Hero Section */
        .hero {
            padding: 80px 0;
            background: linear-gradient(rgba(141, 43, 226, 0.05), rgba(255, 64, 129, 0.05)), url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><rect fill="%23f8f9fa" width="100" height="100"/><path d="M0,50 L100,50" stroke="%23e9ecef" stroke-width="1"/><path d="M50,0 L50,100" stroke="%23e9ecef" stroke-width="1"/></svg>');
            background-size: 30px 30px;
            text-align: center;
        }
        
        .hero h1 {
            font-size: 2.8rem;
            margin-bottom: 20px;
            color: var(--primary);
        }
        
        .hero p {
            font-size: 1.2rem;
            color: var(--gray);
            max-width: 800px;
            margin: 0 auto 40px;
        }
        
        /* Products Section */
        .products {
            padding: 60px 0;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .section-title h2 {
            font-size: 2.2rem;
            display: inline-block;
            position: relative;
            margin-bottom: 15px;
        }
        
        .section-title h2:after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background-color: var(--primary);
            border-radius: 2px;
        }
        
        .filters {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 40px;
        }
        
        .filter-btn {
            background: var(--light);
            border: 1px solid #ddd;
            padding: 8px 20px;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .filter-btn.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        
        .search-box {
            max-width: 500px;
            margin: 0 auto 40px;
            display: flex;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-radius: 50px;
            overflow: hidden;
        }
        
        .search-input {
            flex: 1;
            padding: 15px 25px;
            border: none;
            font-size: 1rem;
            outline: none;
        }
        
        .search-btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 0 25px;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .search-btn:hover {
            background: var(--primary-light);
        }
        
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 30px;
        }
        
        .product-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s;
        }
        
        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .product-image {
            height: 200px;
            overflow: hidden;
            position: relative;
        }
        
        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }
        
        .product-card:hover .product-image img {
            transform: scale(1.1);
        }
        
        .product-category {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--primary);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
        }
        
        .product-info {
            padding: 20px;
        }
        
        .product-info h3 {
            margin-bottom: 10px;
            color: var(--dark);
        }
        
        .product-info p {
            color: var(--gray);
            margin-bottom: 15px;
            font-size: 0.9rem;
        }
        
        .product-price {
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 15px;
            font-size: 1.2rem;
        }
        
        .product-contact {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .product-contact a {
            color: var(--primary);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        /* Add Product Section */
        .add-product {
            background: white;
            padding: 60px 0;
        }
        
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            background: var(--light);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            outline: none;
        }
        
        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }
        
        /* Footer */
        footer {
            background: var(--dark);
            color: white;
            padding: 60px 0 30px;
        }
        
        .footer-content {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-bottom: 40px;
        }
        
        .footer-column {
            flex: 1;
            min-width: 250px;
            margin-bottom: 30px;
        }
        
        .footer-column h3 {
            color: white;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--primary);
            display: inline-block;
        }
        
        .footer-links {
            list-style: none;
        }
        
        .footer-links li {
            margin-bottom: 10px;
        }
        
        .footer-links a {
            color: #ccc;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .footer-links a:hover {
            color: white;
        }
        
        .social-links {
            display: flex;
            gap: 15px;
        }
        
        .social-link {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            text-decoration: none;
            transition: background 0.3s;
        }
        
        .social-link:hover {
            background: var(--primary);
        }
        
        .copyright {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: #ccc;
            font-size: 0.9rem;
        }
        
        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        
        .modal-content {
            background: white;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
            padding: 30px;
            position: relative;
            max-height: 90vh;
            overflow-y: auto;
        }
        
        .close-modal {
            position: absolute;
            top: 15px;
            right: 15px;
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--gray);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }
            
            .hero h1 {
                font-size: 2.2rem;
            }
            
            .footer-column {
                flex: 100%;
            }
            
            .products-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <nav>
                <div class="logo">
                    <i class="fas fa-store"></i>
                    Feirinha Virtual
                </div>
                <ul class="nav-links">
                    <li><a href="#produtos">Produtos</a></li>
                    <li><a href="#adicionar">Adicionar Produto</a></li>
                    <li><a href="#sobre">Sobre</a></li>
                    <li><a href="#" class="btn">Entrar</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Feirinha Virtual de Talentos Locais</h1>
            <p>Descubra e divulgue produtos e serviços feitos por mulheres talentosas da nossa comunidade</p>
            <a href="#adicionar" class="btn">Quero divulgar meu talento</a>
        </div>
    </section>
	
	<?php if (!empty($mensagem)): ?>
		<div class="container">
			<div class="mensagem-status mensagem-<?= $tipoMensagem ?>">
				<?= htmlspecialchars($mensagem) ?>
			</div>
		</div>
	<?php endif; ?>

    <!-- Products Section -->
    <section id="produtos" class="products">
        <div class="container">
            <div class="section-title">
                <h2>Nossos Talentos</h2>
                <p>Conheça os produtos e serviços oferecidos por mulheres da nossa comunidade</p>
            </div>
            
            <div class="search-box">
                <input type="text" class="search-input" placeholder="Buscar produtos ou serviços...">
                <button class="search-btn">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            
            <div class="filters">
                <button class="filter-btn active" data-filter="todos">Todos</button>
                <button class="filter-btn" data-filter="Artesanato">Artesanato</button>
                <button class="filter-btn" data-filter="Culinária">Culinária</button>
                <button class="filter-btn" data-filter="Costura">Costura</button>
                <button class="filter-btn" data-filter="Serviços">Serviços</button>
            </div>
            
            <div class="products-grid" id="products-grid">
                <?php
                // Carregar produtos do banco de dados
                $produtos = buscarProdutos();
                
                if (count($produtos) > 0) {
                    foreach ($produtos as $produto) {
                        echo '
                        <div class="product-card">
                            <div class="product-image">
                                <img src="' . ($produto['imagem'] ?: 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8c2hvcHxlbnwwfHwwfHx8MA%3D%3D&auto=format&fit=crop&w=500&q=60') . '" alt="' . htmlspecialchars($produto['nome']) . '">
                                <div class="product-category">' . htmlspecialchars($produto['categoria_nome']) . '</div>
                            </div>
                            <div class="product-info">
                                <h3>' . htmlspecialchars($produto['nome']) . '</h3>
                                <p>' . htmlspecialchars($produto['descricao']) . '</p>
                                <div class="product-price">' . htmlspecialchars($produto['preco'] ?: 'Preço a combinar') . '</div>
                                <div class="product-contact">
                                    <a href="#"><i class="fas fa-share-alt"></i> Compartilhar</a>
                                    <a href="#" class="view-details" data-id="' . $produto['id'] . '"><i class="fas fa-eye"></i> Ver detalhes</a>
                                </div>
                            </div>
                        </div>';
                    }
                } else {
                    echo '<p class="no-results">Nenhum produto cadastrado ainda. Seja a primeira a divulgar seu talento!</p>';
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Add Product Section -->
    <section id="adicionar" class="add-product">
        <div class="container">
            <div class="section-title">
                <h2>Divulgue seu Talento</h2>
                <p>Cadastre seu produto ou serviço para aparecer em nossa feirinha virtual</p>
            </div>
            
            <div class="form-container">
                <form id="product-form" action="cadastrar_produto.php" method="POST">
                    <div class="form-group">
                        <label for="product-name">Nome do Produto/Serviço *</label>
                        <input type="text" id="product-name" name="nome" class="form-control" placeholder="Ex: Bolsas artesanais em crochê" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="product-category">Categoria *</label>
                        <select id="product-category" name="categoria_id" class="form-control" required>
                            <option value="">Selecione uma categoria</option>
                            <?php
                            $categorias = buscarCategorias();
                            foreach ($categorias as $categoria) {
                                echo '<option value="' . $categoria['id'] . '">' . htmlspecialchars($categoria['nome']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="product-description">Descrição *</label>
                        <textarea id="product-description" name="descricao" class="form-control" placeholder="Descreva seu produto ou serviço..." required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="product-price">Preço (opcional)</label>
                        <input type="text" id="product-price" name="preco" class="form-control" placeholder="Ex: R$ 50,00 ou A combinar">
                    </div>
                    
                    <div class="form-group">
                        <label for="product-contact">Contato *</label>
                        <input type="text" id="product-contact" name="contato" class="form-control" placeholder="WhatsApp, Instagram, telefone..." required>
                    </div>
                    
                    <div class="form-group">
                        <label for="product-image">Imagem (URL)</label>
                        <input type="text" id="product-image" name="imagem" class="form-control" placeholder="Cole o link de uma imagem do seu produto">
                        <small>Ou deixe em branco para usar uma imagem padrão</small>
                    </div>
                    
                    <button type="submit" class="btn">Cadastrar Produto</button>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>Feirinha Virtual</h3>
                    <p>Conectando talentos femininos da comunidade com pessoas que valorizam produtos e serviços artesanais.</p>
                    <div class="social-links">
                        <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
                
                <div class="footer-column">
                    <h3>Links Rápidos</h3>
                    <ul class="footer-links">
                        <li><a href="#produtos">Ver Produtos</a></li>
                        <li><a href="#adicionar">Divulgar Talento</a></li>
                        <li><a href="#sobre">Sobre o Projeto</a></li>
                        <li><a href="#contato">Contato</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h3>Categorias</h3>
                    <ul class="footer-links">
                        <li><a href="#" data-filter="Artesanato">Artesanato</a></li>
                        <li><a href="#" data-filter="Culinária">Culinária</a></li>
                        <li><a href="#" data-filter="Costura">Costura</a></li>
                        <li><a href="#" data-filter="Serviços">Serviços</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="copyright">
                <p>&copy; 2023 Feirinha Virtual - Todos os direitos reservados</p>
            </div>
        </div>
    </footer>

    <!-- Product Detail Modal -->
    <div class="modal" id="product-modal">
        <div class="modal-content">
            <button class="close-modal">&times;</button>
            <img id="modal-image" src="" alt="Produto" style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px; margin-bottom: 20px;">
            <h2 id="modal-title">Nome do Produto</h2>
            <p><strong>Categoria:</strong> <span id="modal-category">Artesanato</span></p>
            <p><strong>Descrição:</strong> <span id="modal-description">Descrição detalhada do produto.</span></p>
            <p><strong>Preço:</strong> <span id="modal-price">R$ 50,00</span></p>
            <p><strong>Contato:</strong> <span id="modal-contact">(11) 99999-9999</span></p>
            <div class="actions" style="margin-top: 20px;">
                <button class="btn" id="modal-contact-btn">Entrar em Contato</button>
            </div>
        </div>
    </div>

    <script>
        // JavaScript para interatividade
        document.addEventListener('DOMContentLoaded', function() {
            // Configurar filtros
            document.querySelectorAll('.filter-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const filter = this.dataset.filter;
                    
                    // Redirecionar para a página com filtro
                    if (filter === 'todos') {
                        window.location.href = 'index.php';
                    } else {
                        window.location.href = 'index.php?categoria=' + encodeURIComponent(filter);
                    }
                });
            });
            
            // Configurar busca
            document.querySelector('.search-btn').addEventListener('click', function() {
                const query = document.querySelector('.search-input').value;
                if (query.trim() !== '') {
                    window.location.href = 'index.php?busca=' + encodeURIComponent(query);
                }
            });
            
            // Permitir busca com Enter
            document.querySelector('.search-input').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    document.querySelector('.search-btn').click();
                }
            });
            
            // Configurar links de categoria no footer
            document.querySelectorAll('.footer-links a[data-filter]').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const filter = this.dataset.filter;
                    window.location.href = 'index.php?categoria=' + encodeURIComponent(filter);
                });
            });
        });
    </script>
</body>
</html>