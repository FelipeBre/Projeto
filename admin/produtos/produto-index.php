<?php
require ('../../conf/conexao.php');

// Filtro opcional
$filtro = isset($_GET['filtro']) ? trim($_GET['filtro']) : '';

// Consulta com ou sem filtro
$sql = "SELECT * FROM produtos";
if ($filtro) {
    $sql .= " WHERE nome LIKE :filtro";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':filtro', "%$filtro%");
} else {
    $stmt = $pdo->prepare($sql);
}
$stmt->execute();
$produtos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Produtos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- <link href="../../assets/css/produtos.css" rel="stylesheet"> -->
    <style>
        :root {
    --primary: #4361ee;
    --primary-light: rgba(67, 97, 238, 0.1);
    --primary-dark: #3a0ca3;
    --secondary: #4cc9f0;
    --accent: #7209b7;
    --success: #4cc9f0;
    --info: #4895ef;
    --warning: #f8961e;
    --danger: #f94144;
    --light: #f8f9fa;
    --dark: #1a1a2e;
    --gray: #6c757d;
    --light-gray: #f1f5f9;
    --glass: rgba(255, 255, 255, 0.95);
    --glass-border: rgba(255, 255, 255, 0.2);
    --card-shadow: 0 8px 32px rgba(31, 38, 135, 0.1);
    --card-shadow-hover: 0 8px 32px rgba(31, 38, 135, 0.2);
    --text-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

/* Estilos Gerais */
body {
    background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
    color: var(--dark);
    line-height: 1.6;
    min-height: 100vh;
    
    backdrop-filter: blur(5px);
}

.container {
    max-width: 1400px;
    margin: 2rem auto;
    background: var(--glass);
    border-radius: 16px;
    box-shadow: var(--card-shadow);
    padding: 2.5rem;
    border: 1px solid var(--glass-border);
    backdrop-filter: blur(10px);
    animation: fadeIn 0.6s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

h2 {
    color: var(--dark);
    font-weight: 700;
    margin-bottom: 2rem;
    font-size: 2rem;
    position: relative;
    display: inline-block;
    text-shadow: var(--text-shadow);
}

h2::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 0;
    width: 50px;
    height: 4px;
    background: linear-gradient(90deg, var(--primary), var(--accent));
    border-radius: 4px;
}

/* Formulário de Busca */
.search-container {
    margin-bottom: 2.5rem;
    position: relative;
}

.input-group {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: var(--card-shadow);
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
}

.input-group:focus-within {
    box-shadow: 0 10px 20px rgba(67, 97, 238, 0.15);
}

.form-control {
    border: none;
    padding: 1rem 1.5rem;
    font-size: 1rem;
    background: var(--glass);
    backdrop-filter: blur(5px);
}

.form-control:focus {
    box-shadow: none;
    background: white;
}

/* Botões */
.btn {
    border-radius: 12px;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    border: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.85rem;
    position: relative;
    overflow: hidden;
    z-index: 1;
}

.btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, var(--primary-dark), var(--primary));
    z-index: -1;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.btn:hover::before {
    opacity: 1;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary), var(--accent));
    color: white;
    box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
}

.btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
}

.btn-success {
    background: linear-gradient(135deg, var(--success), #38b6ff);
    color: white;
    box-shadow: 0 4px 15px rgba(76, 201, 240, 0.3);
}

.btn-success:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(76, 201, 240, 0.4);
}

.btn-warning {
    background: linear-gradient(135deg, var(--warning), #ff9e00);
    color: white;
    box-shadow: 0 4px 15px rgba(248, 150, 30, 0.3);
}

.btn-warning:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(248, 150, 30, 0.4);
}

.btn-danger {
    background: linear-gradient(135deg, var(--danger), #ff2d2d);
    color: white;
    box-shadow: 0 4px 15px rgba(249, 65, 68, 0.3);
}

.btn-danger:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(249, 65, 68, 0.4);
}

/* Tabela */
.table-container {
    border-radius: 16px;
    overflow: hidden;
    box-shadow: var(--card-shadow);
    margin-top: 2rem;
    background: var(--glass);
    backdrop-filter: blur(5px);
    overflow-x: auto;
}

.table {
    width: 100%;
    margin: 0;
    border-collapse: separate;
    border-spacing: 0;
    color: var(--dark);
    min-width: 1000px;
}

.table thead th {
    background: linear-gradient(135deg, var(--primary), var(--accent));
    color: white;
    font-weight: 600;
    padding: 1.25rem;
    border: none;
    text-align: left;
    position: sticky;
    top: 0;
    z-index: 10;
}

.table tbody td {
    padding: 1rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.5);
    vertical-align: middle;
    transition: background 0.2s ease;
}

.table tbody tr:last-child td {
    border-bottom: none;
}

.table-hover tbody tr:hover td {
    background: var(--primary-light);
}

/* Imagens dos Produtos */
.product-img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.product-img:hover {
    transform: scale(1.8) translateY(10px);
    z-index: 100;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
}

.no-image {
    color: var(--gray);
    font-style: italic;
    font-size: 0.9rem;
}

/* Ações */
.actions-cell {
    display: flex;
    gap: 0.75rem;
}

.btn-sm {
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-size: 0.8rem;
    min-width: 80px;
}

/* Mensagem vazia */
.empty-message {
    padding: 3rem;
    text-align: center;
    color: var(--gray);
    font-size: 1.1rem;
    background: var(--glass);
}

.empty-message i {
    font-size: 2rem;
    margin-bottom: 1rem;
    color: var(--primary);
    opacity: 0.7;
}

/* Ícones */
.fas {
    font-size: 0.9rem;
}

/* Floating Action Button */
.fab {
    position: fixed;
    bottom: 2rem;
    right: 2rem;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), var(--accent));
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    z-index: 100;
    font-size: 1.5rem;
}

.fab:hover {
    transform: translateY(-5px) scale(1.1);
    box-shadow: 0 12px 30px rgba(67, 97, 238, 0.5);
}

/* Badges para estoque */
.stock-badge {
    display: inline-block;
    padding: 0.35rem 0.6rem;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 600;
}

.stock-high {
    background-color: rgba(74, 222, 128, 0.2);
    color: #16a34a;
}

.stock-medium {
    background-color: rgba(234, 179, 8, 0.2);
    color: #d97706;
}

.stock-low {
    background-color: rgba(239, 68, 68, 0.2);
    color: #dc2626;
}

/* Responsividade */
@media (max-width: 1200px) {
    .container {
        padding: 1.5rem;
    }
    
    .table thead th {
        padding: 1rem;
    }
    
    .table tbody td {
        padding: 0.75rem;
    }
}

@media (max-width: 992px) {
    body {
        padding: 1rem;
    }
    
    .container {
        margin: 1rem auto;
    }
    
    .product-img {
        width: 60px;
        height: 60px;
    }
}

@media (max-width: 768px) {
    .table-container {
        border-radius: 12px;
    }
    
    .fab {
        bottom: 1.5rem;
        right: 1.5rem;
        width: 50px;
        height: 50px;
        font-size: 1.2rem;
    }
}
    </style>
</head>
<body>
    <div class="container">
        <h2><i class="fas fa-boxes mr-2"></i> Gerenciamento de Produtos</h2>

        <div class="search-container">
            <form method="get">
                <div class="input-group">
                    <input type="text" name="filtro" class="form-control" placeholder="Pesquisar produtos..." value="<?= htmlspecialchars($filtro) ?>">
                    <button class="btn btn-primary">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                </div>
            </form>
        </div>

        <div class="table-container">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th><i class="fas fa-hashtag"></i> ID</th>
                        <th><i class="fas fa-tag"></i> Nome</th>
                        <th><i class="fas fa-align-left"></i> Descrição</th>
                        <th><i class="fas fa-tag"></i> Preço</th>
                        <th><i class="fas fa-boxes"></i> Estoque</th>
                        <th><i class="fas fa-ruler"></i> Tamanho</th>
                        <th><i class="fas fa-list"></i> Categoria</th>
                        <th><i class="fas fa-calendar"></i> Cadastro</th>
                        <th><i class="fas fa-image"></i> Imagem</th>
                        <th><i class="fas fa-cog"></i> Ações</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (count($produtos) > 0): ?>
                    <?php foreach ($produtos as $p): ?>
                        <tr>
                            <td data-label="ID"><?= $p['id'] ?></td>
                            <td data-label="Nome"><?= htmlspecialchars($p['nome']) ?></td>
                            <td data-label="Descrição"><?= htmlspecialchars(substr($p['descricao'], 0, 50)) . (strlen($p['descricao']) > 50 ? '...' : '') ?></td>
                            <td data-label="Preço">R$ <?= number_format($p['preco'], 2, ',', '.') ?></td>
                            <td data-label="Estoque">
                                <span class="stock-badge <?= $p['estoque'] > 20 ? 'stock-high' : ($p['estoque'] > 5 ? 'stock-medium' : 'stock-low') ?>">
                                    <?= $p['estoque'] ?> un
                                </span>
                            </td>
                            <td data-label="Tamanho"><?= htmlspecialchars($p['tamanho']) ?></td>
                            <td data-label="Categoria"><?= htmlspecialchars($p['categoria']) ?></td>
                            <td data-label="Cadastro"><?= date('d/m/Y H:i', strtotime($p['data_cadastro'])) ?></td>
                            <td data-label="Imagem">
                                <?php if (!empty($p['imagem'])): ?>
                                    <img src="<?= htmlspecialchars($p['imagem']) ?>" alt="Imagem do Produto" class="product-img">
                                <?php else: ?>
                                    <span class="no-image">Sem imagem</span>
                                <?php endif ?>
                            </td>
                            <td class="actions-cell">
                                <a href="produtos/edit.php?id=<?= $p['id'] ?>" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <a href="delete.php?id=<?= $p['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este produto?')">
                                    <i class="fas fa-trash"></i> Excluir
                                </a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" class="empty-message">
                            <i class="fas fa-box-open"></i>
                            <p>Nenhum produto encontrado</p>
                        </td>
                    </tr>
                <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>

    <a href="../public/cad_produtos.php" class="fab">
        <i class="fas fa-plus"></i>
    </a>
</body>
</html>