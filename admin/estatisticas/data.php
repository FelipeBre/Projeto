<?php
header('Content-Type: application/json');

try {
    // Conexão com o banco de dados do projeto
    $pdo = new PDO('mysql:host=localhost;dbname=powerpc', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Total de produtos
    $stmtProdutos = $pdo->query("SELECT COUNT(*) as total FROM produtos");
    $totalProdutos = $stmtProdutos->fetch(PDO::FETCH_ASSOC)['total'];

    // Total de pedidos
    $stmtPedidos = $pdo->query("SELECT COUNT(*) as total FROM pedidos");
    $totalPedidos = $stmtPedidos->fetch(PDO::FETCH_ASSOC)['total'];

    // Pedidos por mês (últimos 6 meses)
    $stmtMensal = $pdo->query("
        SELECT DATE_FORMAT(data_pedido, '%Y-%m') as mes, COUNT(*) as total
        FROM pedidos
        GROUP BY mes
        ORDER BY mes DESC
        LIMIT 6
    ");
    $pedidosMensais = $stmtMensal->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'totalProdutos' => $totalProdutos,
        'totalPedidos' => $totalPedidos,
        'pedidosMensais' => array_reverse($pedidosMensais)
    ]);

} catch (PDOException $e) {
    echo json_encode(['erro' => $e->getMessage()]);
}
?>
