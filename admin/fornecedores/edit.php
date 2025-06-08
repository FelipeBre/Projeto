<?php
require_once('../../conf/conexao.php');

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    die("ID inválido");
}

$stmt = $pdo->prepare("SELECT * FROM fornecedores WHERE id_fornece = ?");
$stmt->execute([$id]);
$fornecedor = $stmt->fetch();

if (!$fornecedor) {
    die("Fornecedor não encontrado");
}

$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $telefone = filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_STRING);
    $endereco = filter_input(INPUT_POST, 'endereco', FILTER_SANITIZE_STRING);

    if (strlen($nome) < 3) {
        $erros[] = "Nome deve ter pelo menos 3 caracteres.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "E-mail inválido.";
    }

    if (empty($erros)) {
        $sql = "UPDATE fornecedores SET nome = :nome, email = :email, telefone = :telefone, endereco = :endereco WHERE id_fornece = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nome' => $nome,
            ':email' => $email,
            ':telefone' => $telefone,
            ':endereco' => $endereco,
            ':id' => $id
        ]);
        header("Location: fornecedor-index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Fornecedor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
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

        h2 {
            color: var(--dark);
            font-weight: 700;
            margin-bottom: 1.5rem;
            position: relative;
            display: inline-block;
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
        .form-control {
            border-radius: 12px;
            padding: 1rem 1.25rem;
            border: 1px solid var(--light-gray);
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
        }

        .form-label {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }

        /* Alertas */
        .alert {
            border-radius: 12px;
            padding: 1.25rem 1.5rem;
            border: none;
            box-shadow: var(--card-shadow);
        }

        .alert-danger {
            background: rgba(249, 65, 68, 0.1);
            color: var(--dark);
            border-left: 4px solid var(--danger);
        }

        /* Responsividade */
        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }
            
            .edit-container {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="edit-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-edit me-2"></i> Editar Fornecedor</h2>
            <a href="../../admin/index.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Voltar
            </a>
        </div>

        <?php if (!empty($erros)): ?>
            <div class="alert alert-danger mb-4">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <?php foreach ($erros as $erro): ?>
                    <p class="mb-1"><?= htmlspecialchars($erro) ?></p>
                <?php endforeach ?>
            </div>
        <?php endif ?>

        <form method="POST">
            <div class="mb-4">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control" name="nome" id="nome" 
                       value="<?= htmlspecialchars($fornecedor['nome']) ?>" required>
            </div>
            
            <div class="mb-4">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" class="form-control" name="email" id="email" 
                       value="<?= htmlspecialchars($fornecedor['email']) ?>" required>
            </div>
            
            <div class="mb-4">
                <label for="telefone" class="form-label">Telefone</label>
                <input type="text" class="form-control" name="telefone" id="telefone" 
                       value="<?= htmlspecialchars($fornecedor['telefone']) ?>">
            </div>
            
            <div class="mb-4">
                <label for="endereco" class="form-label">Endereço</label>
                <input type="text" class="form-control" name="endereco" id="endereco" 
                       value="<?= htmlspecialchars($fornecedor['endereco']) ?>">
            </div>
            
            <div class="text-end mt-5">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Salvar Alterações
                </button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>