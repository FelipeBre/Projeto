<?php
session_start();
require_once('../../conf/conexao.php');

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    die("ID inválido");
}

$stmt = $pdo->prepare("SELECT * FROM pedidos WHERE id = ?");
$stmt->execute([$id]);
$pedido = $stmt->fetch();

if (!$pedido) {
    die("Pedido não encontrado");
}

$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cliente_nome = filter_input(INPUT_POST, 'cliente_nome', FILTER_SANITIZE_STRING);
    $produto = filter_input(INPUT_POST, 'produto', FILTER_SANITIZE_STRING);
    $quantidade = filter_input(INPUT_POST, 'quantidade', FILTER_VALIDATE_INT);
    $preco_total = str_replace(',', '.', preg_replace('/[^\d,]/', '', $_POST['preco_total']));
    $data_pedido = $_POST['data_pedido'];
    $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);

    // Validações
    if (strlen($cliente_nome) < 2) $erros[] = "Nome do cliente deve ter pelo menos 2 caracteres.";
    if (strlen($produto) < 2) $erros[] = "Produto inválido.";
    if (!is_numeric($preco_total) || $preco_total <= 0) $erros[] = "Preço inválido.";
    if ($quantidade <= 0) $erros[] = "Quantidade deve ser maior que zero.";
    if (!$data_pedido) $erros[] = "Data do pedido inválida.";

    if (empty($erros)) {
        try {
            $sql = "UPDATE pedidos SET 
                        cliente_nome = :cliente_nome,
                        produto = :produto,
                        quantidade = :quantidade,
                        preco_total = :preco_total,
                        data_pedido = :data_pedido,
                        status = :status
                    WHERE id = :id";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':cliente_nome' => $cliente_nome,
                ':produto' => $produto,
                ':quantidade' => $quantidade,
                ':preco_total' => $preco_total,
                ':data_pedido' => $data_pedido,
                ':status' => $status,
                ':id' => $id
            ]);

            header("Location: pedidos-index.php");
            exit();
        } catch (PDOException $e) {
            $erros[] = "Erro ao atualizar pedido: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Pedido | POWER PC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/a2d9d6c6e1.js" crossorigin="anonymous"></script>
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

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            color: var(--dark);
            line-height: 1.6;
            min-height: 100vh;
            padding: 2rem;
            backdrop-filter: blur(5px);
        }

        .edit-container {
            max-width: 800px;
            margin: 0 auto;
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

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--light-gray);
        }

        h2 {
            color: var(--dark);
            font-weight: 700;
            margin: 0;
            position: relative;
            display: inline-block;
            font-size: 1.8rem;
        }

        h2 i {
            color: var(--primary);
            margin-right: 0.75rem;
        }

        h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border-radius: 4px;
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

        .btn-secondary {
            background: linear-gradient(135deg, var(--gray), #5c636a);
            color: white;
            box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
        }

        .btn-secondary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(108, 117, 125, 0.4);
        }

        /* Formulário */
        .form-control, .form-select {
            border-radius: 12px;
            padding: 1rem 1.25rem;
            border: 1px solid var(--light-gray);
            transition: all 0.3s ease;
            background-color: var(--light);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
            background-color: white;
        }

        .form-label {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.5rem;
            display: block;
        }

        .mb-3 {
            margin-bottom: 1.5rem !important;
        }

        /* Alertas */
        .alert {
            border-radius: 12px;
            padding: 1.25rem 1.5rem;
            border: none;
            box-shadow: var(--card-shadow);
            margin-bottom: 2rem;
        }

        .alert-danger {
            background: rgba(249, 65, 68, 0.1);
            color: var(--dark);
            border-left: 4px solid var(--danger);
        }

        .alert-danger p {
            margin-bottom: 0.5rem;
        }

        .alert-danger p:last-child {
            margin-bottom: 0;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }
            
            .edit-container {
                padding: 1.5rem;
            }
            
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
<div class="edit-container">
    <div class="page-header">
        <h2><i class="fas fa-edit"></i> Editar Pedido</h2>
        <a href="pedidos-index.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Voltar
        </a>
    </div>

    <?php if (!empty($erros)): ?>
        <div class="alert alert-danger">
            <?php foreach ($erros as $erro): ?>
                <p><?= htmlspecialchars($erro) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="cliente_nome" class="form-label">Nome do Cliente</label>
            <input type="text" name="cliente_nome" id="usuario_id" class="form-control" required
                   value="<?= htmlspecialchars($_POST['usuario_id'] ?? $pedido['usuario_id']) ?>" />
        </div>

        <div class="mb-3">
            <label for="produto" class="form-label">Produto</label>
            <input type="text" name="produto" id="produto" class="form-control" required
                   value="<?= htmlspecialchars($_POST['produto'] ?? $pedido['produto']) ?>" />
        </div>

        <div class="mb-3">
            <label for="quantidade" class="form-label">Quantidade</label>
            <input type="number" name="quantidade" id="quantidade" class="form-control" required
                   value="<?= htmlspecialchars($_POST['quantidade'] ?? $pedido['quantidade']) ?>" />
        </div>

        <div class="mb-3">
            <label for="preco_total" class="form-label">Preço Total</label>
            <input type="text" name="preco_total" id="preco_total" class="form-control" required
                   value="<?= htmlspecialchars($_POST['preco_total'] ?? number_format($pedido['preco_total'], 2, ',', '')) ?>" />
        </div>

        <div class="mb-3">
            <label for="data_pedido" class="form-label">Data do Pedido</label>
            <input type="date" name="data_pedido" id="data_pedido" class="form-control" required
                   value="<?= htmlspecialchars($_POST['data_pedido'] ?? $pedido['data_pedido']) ?>" />
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select" required>
                <option value="Pendente" <?= ($pedido['status'] == 'Pendente') ? 'selected' : '' ?>>Pendente</option>
                <option value="Processando" <?= ($pedido['status'] == 'Processando') ? 'selected' : '' ?>>Processando</option>
                <option value="Concluído" <?= ($pedido['status'] == 'Concluído') ? 'selected' : '' ?>>Concluído</option>
                <option value="Cancelado" <?= ($pedido['status'] == 'Cancelado') ? 'selected' : '' ?>>Cancelado</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i> Salvar Alterações
        </button>
    </form>
</div>
</body>
</html>