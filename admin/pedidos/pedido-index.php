<?php
require ('../../conf/conexao.php');

$filtro = isset($_GET['filtro']) ? trim($_GET['filtro']) : '';

$sql = "SELECT * FROM pedidos";
if ($filtro) {
    $sql .= " WHERE status LIKE :filtro";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':filtro', "%$filtro%");
} else {
    $stmt = $pdo->prepare($sql);
}
$stmt->execute();
$pedidos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Pedidos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="../../assets/css/pedidos.css" rel="stylesheet">
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

/* Status dos Pedidos */
.status-badge {
    display: inline-block;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: capitalize;
}

.status-pendente {
    background-color: rgba(248, 150, 30, 0.2);
    color: #d97706;
}

.status-processando {
    background-color: rgba(56, 189, 248, 0.2);
    color: #0ea5e9;
}

.status-enviado {
    background-color: rgba(16, 185, 129, 0.2);
    color: #10b981;
}

.status-entregue {
    background-color: rgba(34, 197, 94, 0.2);
    color: #22c55e;
}

.status-cancelado {
    background-color: rgba(239, 68, 68, 0.2);
    color: #ef4444;
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
        <h2><i class="fas fa-shopping-cart mr-2"></i> Gerenciamento de Pedidos</h2>

        <div class="search-container">
            <form method="get">
                <div class="input-group">
                    <input type="text" name="filtro" class="form-control" placeholder="Filtrar por status..." value="<?= htmlspecialchars($filtro) ?>">
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
                        <th><i class="fas fa-user"></i> Usuário</th>
                        <th><i class="fas fa-calendar"></i> Data</th>
                        <th><i class="fas fa-dollar-sign"></i> Total</th>
                        <th><i class="fas fa-info-circle"></i> Status</th>
                        <th><i class="fas fa-truck"></i> Entrega</th>
                        <th><i class="fas fa-cog"></i> Ações</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (count($pedidos) > 0): ?>
                    <?php foreach ($pedidos as $p): ?>
                        <tr>
                            <td data-label="ID">#<?= $p['id'] ?></td>
                            <td data-label="Usuário"><?= $p['usuario_id'] ?></td>
                            <td data-label="Data"><?= date('d/m/Y H:i', strtotime($p['data_pedido'])) ?></td>
                            <td data-label="Total">R$ <?= number_format($p['total'], 2, ',', '.') ?></td>
                            <td data-label="Status">
                                <span class="status-badge status-<?= strtolower($p['status']) ?>">
                                    <?= htmlspecialchars(ucfirst($p['status'])) ?>
                                </span>
                            </td>
                            <td data-label="Entrega">
                                <div class="text-truncate" style="max-width: 200px;" title="<?= htmlspecialchars($p['endereco_entrega']) ?>">
                                    <?= nl2br(htmlspecialchars($p['endereco_entrega'])) ?>
                                </div>
                            </td>
                            <td class="actions-cell">
                                <a href="pedidos/edit.php?id=<?= $p['id'] ?>" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <a href="pedidos/delete.php?id=<?= $p['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este pedido?')">
                                    <i class="fas fa-trash"></i> Excluir
                                </a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="empty-message">
                            <i class="fas fa-shopping-bag"></i>
                            <p>Nenhum pedido encontrado</p>
                        </td>
                    </tr>
                <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>

    <a href="../public/cad_pedidos.php" class="fab">
        <i class="fas fa-plus"></i>
    </a>
</body>
</html>