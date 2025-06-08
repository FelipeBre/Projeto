<?php // include('../includes/header.php'); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Estatísticas</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <h2>Painel de Estatísticas</h2>

        <div class="dados-gerais">
            <h3>Total de Produtos: <span id="totalProdutos">...</span></h3>
            <h3>Total de Pedidos: <span id="totalPedidos">...</span></h3>
        </div>

        <div class="grafico">
            <canvas id="graficoPedidos" width="600" height="300"></canvas>
        </div>
    </div>

    <script src="js/script.js"></script>
</body>
</html>
