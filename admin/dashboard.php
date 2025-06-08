<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - PowerPC Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        
        }

        .main-content {
            padding: 2rem;
            margin-left: 100px;
            transition: all 0.4s;
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .page-header {
            margin-bottom: 2.5rem;
        }

        .page-header h1 {
            font-weight: 700;
            font-size: 2.25rem;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            display: inline-block;
            margin-bottom: 0.5rem;
            text-shadow: var(--text-shadow);
        }

        .page-header h1::after {
            content: '';
            display: block;
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            margin-top: 0.5rem;
            border-radius: 4px;
        }

        .last-update {
            color: var(--gray);
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Cards de Estatísticas */
        .stats-card {
            border: none;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            margin-bottom: 1.5rem;
            background: var(--glass);
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--card-shadow-hover);
        }

        .stats-card:hover::before {
            opacity: 1;
        }

        .stats-card .card-body {
            padding: 1.75rem;
            position: relative;
        }

        .stats-card .card-icon {
            position: absolute;
            right: 1.5rem;
            top: 1.5rem;
            font-size: 2.5rem;
            opacity: 0.2;
            transition: transform 0.3s ease;
        }

        .stats-card:hover .card-icon {
            transform: scale(1.1);
        }

        .stats-card .card-title {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            color: var(--gray);
            margin-bottom: 0.75rem;
        }

        .stats-card .card-value {
            font-size: 2rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 0.75rem;
            font-feature-settings: 'tnum';
            font-variant-numeric: tabular-nums;
        }

        .stats-card .card-change {
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 5px;
            font-weight: 600;
        }

        .stats-card .card-change.positive {
            color: var(--success);
        }

        .stats-card .card-change.negative {
            color: var(--danger);
        }

        .stats-card .card-change.neutral {
            color: var(--gray);
        }

        /* Cores específicas para cada card */
        .card-products .card-icon {
            color: var(--primary);
        }

        .card-users .card-icon {
            color: var(--success);
        }

        .card-orders .card-icon {
            color: var(--warning);
        }

        .card-sales .card-icon {
            color: var(--danger);
        }

        /* Cards principais */
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            margin-bottom: 1.5rem;
            background: var(--glass);
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: var(--card-shadow-hover);
        }

        .card-header {
            background: var(--glass);
            border-bottom: 1px solid var(--glass-border);
            font-weight: 700;
            padding: 1.25rem 1.5rem;
            border-radius: 16px 16px 0 0 !important;
            color: var(--dark);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-header span {
            font-size: 1.1rem;
        }

        /* Botões */
        .btn-action {
            background: linear-gradient(135deg, var(--primary), var(--accent));
            border: none;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
            position: relative;
            overflow: hidden;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-action::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .btn-action:hover {
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(67, 97, 238, 0.4);
        }

        .btn-action:hover::before {
            opacity: 1;
        }

        /* Gráficos */
        .chart-container {
            position: relative;
            height: 300px;
            padding: 1rem;
        }

        /* Status dos pedidos */
        .empty-state {
            text-align: center;
            padding: 3rem 0;
        }

        .empty-state i {
            font-size: 3.5rem;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 1.5rem;
        }

        .empty-state p {
            color: var(--gray);
            margin-bottom: 1.5rem;
            font-size: 1rem;
        }

        /* Atividades recentes */
        .activity-item {
            padding: 1.25rem;
            border-bottom: 1px solid var(--glass-border);
            transition: background-color 0.3s ease;
            display: flex;
            align-items: center;
        }

        .activity-item:hover {
            background-color: var(--primary-light);
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            margin-right: 1.25rem;
            flex-shrink: 0;
            color: white;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            box-shadow: 0 4px 6px rgba(67, 97, 238, 0.2);
        }

        .activity-content {
            flex: 1;
        }

        .activity-item h6 {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.25rem;
        }

        .activity-item p {
            color: var(--gray);
            margin-bottom: 0.25rem;
            font-size: 0.9rem;
        }

        .activity-item small {
            color: var(--gray);
            font-size: 0.8rem;
            font-weight: 500;
        }

        /* Dropdown */
        .dropdown-menu {
            border: none;
            box-shadow: var(--card-shadow);
            border-radius: 12px;
            padding: 0.5rem;
            background: var(--glass);
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
        }

        .dropdown-item {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background-color: var(--primary-light);
            color: var(--primary);
        }

        /* Responsividade */
        @media (max-width: 1200px) {
            .stats-card .card-value {
                font-size: 1.75rem;
            }
        }

        @media (max-width: 992px) {
            .main-content {
                padding: 1.5rem;
            }
            
            .page-header h1 {
                font-size: 2rem;
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }
            
            .page-header h1 {
                font-size: 1.75rem;
            }
            
            .stats-card .card-body {
                padding: 1.5rem;
            }
            
            .stats-card .card-value {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="main-content">
        <div class="container-fluid px-4">
            <div class="page-header">
                <h1>Dashboard</h1>
                <div class="last-update">Última atualização: <?php echo date('d/m/Y H:i'); ?></div>
            </div>
            
            <div class="row g-4">
                <!-- Card Produtos -->
                <div class="col-xl-3 col-md-6">
                    <div class="stats-card card-products">
                        <div class="card-body">
                            <i class="fas fa-boxes card-icon"></i>
                            <div class="card-title">Produtos</div>
                            <div class="card-value">0</div>
                            <div class="card-change neutral">
                                <i class="fas fa-equals"></i> 70% este mês
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Usuários -->
                <div class="col-xl-3 col-md-6">
                    <div class="stats-card card-users">
                        <div class="card-body">
                            <i class="fas fa-users card-icon"></i>
                            <div class="card-title">Usuários</div>
                            <div class="card-value">8</div>
                            <div class="card-change positive">
                                <i class="fas fa-arrow-up"></i> 12% este mês
                            </div>
                            <div class="text-center mt-3">
                                <a href="../public/cadastro_new_V2.php" class="btn btn-action">
                                    <i class="fas fa-user-plus me-1"></i> Novo Usuário
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Pedidos -->
                <div class="col-xl-3 col-md-6">
                    <div class="stats-card card-orders">
                        <div class="card-body">
                            <i class="fas fa-shopping-cart card-icon"></i>
                            <div class="card-title">Pedidos</div>
                            <div class="card-value">0</div>
                            <div class="card-change negative">
                                <i class="fas fa-arrow-down"></i> 0% este mês
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Vendas -->
                <div class="col-xl-3 col-md-6">
                    <div class="stats-card card-sales">
                        <div class="card-body">
                            <i class="fas fa-chart-line card-icon"></i>
                            <div class="card-title">Vendas (R$)</div>
                            <div class="card-value">R$ 0,00</div>
                            <div class="card-change neutral">
                                <i class="fas fa-equals"></i> Sem variação
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráficos e informações -->
            <div class="row mt-4 g-4">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>Vendas Mensais</span>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown">
                                    Este ano
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <li><a class="dropdown-item" href="#">Este ano</a></li>
                                    <li><a class="dropdown-item" href="#">Últimos 6 meses</a></li>
                                    <li><a class="dropdown-item" href="#">Últimos 3 meses</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="vendasMensaisChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status dos pedidos -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            Status dos Pedidos
                        </div>
                        <div class="card-body">
                            <div class="empty-state">
                                <i class="fas fa-shopping-bag"></i>
                                <p>Nenhum pedido registrado.</p>
                                <div class="text-center mt-3">
                                <a href="produtos/produto-index.php" class="btn btn-action">
                                    <i class="fas fa-user-plus me-1"></i> Novo Produto
                                </a>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Últimas atividades -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            Últimas Atividades
                        </div>
                        <div class="card-body p-0">
                            <div class="activity-item">
                                <div class="activity-icon">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <div class="activity-content">
                                    <h6>Novo usuário registrado</h6>
                                    <p>Administrador adicionou um novo usuário</p>
                                    <small>Hoje, 10:45 AM</small>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-icon" style="background: linear-gradient(135deg, var(--warning), #ff9e00);">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div class="activity-content">
                                    <h6>Atualização necessária</h6>
                                    <p>Sistema requer atualização para a versão 2.5</p>
                                    <small>Ontem, 3:30 PM</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script do gráfico -->
    <!-- Script do gráfico -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  fetch('estatisticas/data.php')
    .then(response => {
      if (!response.ok) throw new Error('Erro na requisição: ' + response.status);
      return response.json();
    })
    .then(data => {
      if (data.erro) {
        console.error('Erro no servidor:', data.erro);
        return;
      }

      const labels = data.pedidosMensais.map(item => item.mes);
      const valores = data.pedidosMensais.map(item => item.total);

      const ctx = document.getElementById('vendasMensaisChart').getContext('2d');
      const gradient = ctx.createLinearGradient(0, 0, 0, 300);
      gradient.addColorStop(0, 'rgba(67, 97, 238, 0.8)');
      gradient.addColorStop(1, 'rgba(67, 97, 238, 0.1)');

      new Chart(ctx, {
        type: 'bar',
        data: {
          labels: labels,
          datasets: [{
            label: 'Pedidos por Mês',
            data: valores,
            backgroundColor: gradient,
            borderColor: 'rgba(67, 97, 238, 1)',
            borderWidth: 1,
            borderRadius: 6,
            borderSkipped: false
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                stepSize: 1
              }
            }
          },
          plugins: {
            legend: {
              display: true
            },
            tooltip: {
              mode: 'index',
              intersect: false
            }
          }
        }
      });
    })
    .catch(error => {
      console.error('Erro ao carregar os dados do gráfico:', error);
    });
});
</script>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>