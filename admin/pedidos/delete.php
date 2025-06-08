<?php
session_start();
require_once('../../conf/conexao.php');

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = intval($_GET['id']);

// Delete fornecedor
$sql = "DELETE FROM pedido_itens WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);

header('Location: index.php');
exit;
?>
