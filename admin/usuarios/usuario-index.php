<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Clientes Cadastrados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="../../assets/css/usuarios.css" rel="stylesheet">
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
    max-width: 1200px;
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
    margin-bottom: 1rem;
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

p.subtitle {
    color: var(--gray);
    font-size: 1.1rem;
    margin-bottom: 2rem;
    max-width: 800px;
    line-height: 1.7;
}

/* Tabela */
.table-container {
    border-radius: 16px;
    overflow: hidden;
    box-shadow: var(--card-shadow);
    margin-top: 2rem;
    background: var(--glass);
    backdrop-filter: blur(5px);
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
    padding: 1.25rem;
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

/* Ícones de usuário */
.user-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), var(--accent));
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    font-weight: 600;
}

.user-name {
    display: flex;
    align-items: center;
}

/* Alertas */
.alert {
    border-radius: 12px;
    padding: 1.5rem;
    border: none;
    box-shadow: var(--card-shadow);
    background: var(--glass);
    backdrop-filter: blur(5px);
}

.alert-info {
    background: rgba(56, 189, 248, 0.1);
    color: var(--dark);
    border-left: 4px solid var(--info);
}

.alert-danger {
    background: rgba(249, 65, 68, 0.1);
    color: var(--dark);
    border-left: 4px solid var(--danger);
}

/* Responsividade */
@media (max-width: 992px) {
    body {
        padding: 1.5rem;
    }
    
    .container {
        padding: 1.75rem;
    }
    
    .table thead th,
    .table tbody td {
        padding: 1rem;
    }
}

@media (max-width: 768px) {
    body {
        padding: 1rem;
    }
    
    .container {
        padding: 1.5rem;
    }
    
    h2 {
        font-size: 1.75rem;
    }
    
    p.subtitle {
        font-size: 1rem;
    }
    
    .table thead th,
    .table tbody td {
        padding: 0.75rem;
        font-size: 0.9rem;
    }
    
    .user-icon {
        width: 32px;
        height: 32px;
        font-size: 0.9rem;
    }
}
    </style>
</head>
<body>
    <div class="container">
        <h2><i class="fas fa-users mr-2"></i> Clientes Cadastrados</h2>
        <p class="subtitle">Visualize abaixo os usuários que se cadastraram no site. Esta lista mostra apenas usuários do tipo "cliente".</p>
<?php
include('../../conf/conexao.php');
?>


<?php
try {
    // Consulta apenas usuários do tipo "cliente"
    $stmt = $pdo->prepare("SELECT id, nome, email FROM usuarios WHERE tipo = 'usuario'");
    $stmt->execute();

    if ($stmt->rowCount() > 0): ?>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($usuario = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?= htmlspecialchars($usuario['id']) ?></td>
                        <td><?= htmlspecialchars($usuario['nome']) ?></td>
                        <td><?= htmlspecialchars($usuario['email']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">Nenhum cliente cadastrado no momento.</div>
    <?php endif;

} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>Erro ao buscar usuários: " . $e->getMessage() . "</div>";
}
?>

