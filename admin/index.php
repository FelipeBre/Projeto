<?php
session_start();
if ($_SESSION['tipo'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
            color: var(--dark);
            line-height: 1.6;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            color: white;
            min-height: 100vh;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: fixed;
            z-index: 1000;
        }

        .sidebar-brand {
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 1rem;
        }

        .sidebar-brand h5 {
            color: white;
            font-weight: 600;
            margin: 0;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.75rem 1.25rem;
            border-radius: 8px;
            margin: 0.25rem 0.75rem;
            transition: all 0.3s ease;
            font-weight: 500;
            display: flex;
            align-items: center;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(5px);
        }

        .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            font-weight: 600;
        }

        .nav-link i {
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
        }

        /* Conteúdo Principal */
        .main-content {
            margin-left: 250px;
            padding: 2rem;
            transition: all 0.3s ease;
        }

        /* Card de Boas-Vindas */
        .welcome-card {
            border: none;
            border-radius: 16px;
            background: var(--glass);
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            overflow: hidden;
        }

        .welcome-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--card-shadow-hover);
        }

        .welcome-card .card-body {
            padding: 2.5rem;
        }

        .welcome-card h2 {
            font-weight: 700;
            font-size: 2rem;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            display: inline-block;
            margin-bottom: 1rem;
            position: relative;
        }

        .welcome-card h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border-radius: 4px;
        }

        .welcome-card .lead {
            font-size: 1.1rem;
            color: var(--dark);
            margin-bottom: 1.5rem;
            line-height: 1.7;
        }

        /* Alert */
        .welcome-alert {
            border-radius: 12px;
            background: rgba(56, 189, 248, 0.1);
            border-left: 4px solid var(--info);
            padding: 1.25rem;
            margin: 1.5rem 0;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .welcome-alert strong {
            color: var(--primary-dark);
            font-weight: 600;
        }

        .welcome-alert i {
            margin-right: 0.5rem;
        }

        /* Divisor */
        .welcome-divider {
            border: none;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(67, 97, 238, 0.2), transparent);
            margin: 2rem 0;
        }

        /* Responsividade */
        @media (max-width: 992px) {
            .sidebar {
                width: 220px;
            }
            .main-content {
                margin-left: 220px;
                padding: 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
                padding: 1.5rem;
            }
            
            .welcome-card .card-body {
                padding: 1.75rem;
            }
            
            .welcome-card h2 {
                font-size: 1.75rem;
            }
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <?php include('../includes/siderbar.php'); ?>

        <!-- Conteúdo Principal -->
        <div class="main-content w-100" id="mainContent">
            <div class="container">
                <div class="welcome-card">
                    <div class="card-body">
                        <h2><i class="fas fa-hand-sparkles mr-2"></i> Bem-vindo(a) ao Painel Administrativo</h2>
                        <p class="lead">Gerencie todas as operações do sistema de forma eficiente através deste painel. Utilize o menu lateral para navegar entre as diferentes seções.</p>
                        
                        <div class="welcome-alert">
                            <i class="fas fa-info-circle"></i> Clique em uma opção da <strong>sidebar</strong> (menu lateral) para começar.<br>
                            Exemplo: <strong>Dashboard, Usuários, Produtos...</strong>
                        </div>

                        <hr class="welcome-divider">

                        <p class="text-muted">Se precisar de ajuda, entre em contato com o suporte técnico ou consulte a documentação interna.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.nav-link[data-page]').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const page = this.getAttribute('data-page');
                    
                    // Adiciona classe ativa ao link clicado
                    document.querySelectorAll('.nav-link').forEach(lnk => lnk.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Carrega o conteúdo dinamicamente
                    fetch(page)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error("Página não encontrada");
                            }
                            return response.text();
                        })
                        .then(html => {
                            document.getElementById('mainContent').innerHTML = html;
                        })
                        .catch(error => {
                            document.getElementById('mainContent').innerHTML = `
                                <div class="container mt-5">
                                    <div class="alert alert-danger">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        Erro ao carregar a página: ${error.message}
                                    </div>
                                </div>
                            `;
                        });
                });
            });
        });
    </script>
</body>
</html>