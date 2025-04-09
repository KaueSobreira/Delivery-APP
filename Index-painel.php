<?php
    include('php/conexao/conection.php');
    
    $sqlConfig = "SELECT * FROM configuracoes_empresa LIMIT 1";
    $resultConfig = $conn->query($sqlConfig);
    
    if ($resultConfig && $resultConfig->num_rows > 0) {
        $config = $resultConfig->fetch_assoc();
    }
    
    $sqlCountProdutos = "SELECT COUNT(*) as total FROM produtos";
    $resultProdutos = $conn->query($sqlCountProdutos);
    $totalProdutos = ($resultProdutos) ? $resultProdutos->fetch_assoc()['total'] : 0;
    
    $sqlCountCategorias = "SELECT COUNT(*) as total FROM categoria";
    $resultCategorias = $conn->query($sqlCountCategorias);
    $totalCategorias = ($resultCategorias) ? $resultCategorias->fetch_assoc()['total'] : 0;
    
    $sqlCountPromocoes = "SELECT COUNT(*) as total FROM produtos WHERE em_promocao = 1";
    $resultPromocoes = $conn->query($sqlCountPromocoes);
    $totalPromocoes = ($resultPromocoes) ? $resultPromocoes->fetch_assoc()['total'] : 0;
    
    $conn->close();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo - <?php echo isset($config) ? $config['nome_empresa'] : 'Hamburgueria'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
    <div class="wrapper">
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3><?php echo isset($config) ? $config['nome_empresa'] : 'Hamburgueria'; ?></h3>
                <strong>HB</strong>
            </div>

            <ul class="list-unstyled components">
                <li class="active">
                    <a href="Index-painel.php">
                        <i class="fas fa-home"></i>
                        <span>Painel</span>
                    </a>
                </li>
                <li>
                    <a href="Index-cardapio.php" target="_blank">
                        <i class="fas fa-utensils"></i>
                        <span>Cardápio</span>
                    </a>
                </li>
                <li>
                    <a href="pages/produtos.php">
                        <i class="fas fa-hamburger"></i>
                        <span>Produtos</span>
                    </a>
                </li>
                <li>
                    <a href="pages/categoria.php">
                        <i class="fas fa-tags"></i>
                        <span>Categorias</span>
                    </a>
                </li>
                <li>
                    <a href="pages/configuracoes_empresa.php">
                        <i class="fas fa-cog"></i>
                        <span>Configurações</span>
                    </a>
                </li>
            </ul>
        </nav>
        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div>
                        <h5 class="mb-0">Painel de Controle</h5>
                    </div>
                </div>
            </nav>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h2 class="card-title text-center mb-4">Bem-vindo ao Painel de Controle</h2>
                                <p class="text-center">Gerencie seu cardápio e produtos de forma fácil e intuitiva.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-3 mb-4">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <i class="fas fa-hamburger fa-3x mb-3"></i>
                                <h4><?php echo $totalProdutos; ?></h4>
                                <p>Produtos</p>
                            </div>
                            <a href="pages/produtos.php" class="card-footer bg-primary-dark text-white text-decoration-none">
                                <small>Ver Detalhes <i class="fas fa-arrow-circle-right"></i></small>
                            </a>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-4">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <i class="fas fa-tags fa-3x mb-3"></i>
                                <h4><?php echo $totalCategorias; ?></h4>
                                <p>Categorias</p>
                            </div>
                            <a href="pages/categoria.php" class="card-footer bg-success-dark text-white text-decoration-none">
                                <small>Ver Detalhes <i class="fas fa-arrow-circle-right"></i></small>
                            </a>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-4">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center">
                                <i class="fas fa-fire fa-3x mb-3"></i>
                                <h4><?php echo $totalPromocoes; ?></h4>
                                <p>Promoções</p>
                            </div>
                            <a href="pages/produtos.php" class="card-footer bg-warning-dark text-white text-decoration-none">
                                <small>Ver Detalhes <i class="fas fa-arrow-circle-right"></i></small>
                            </a>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-4">
                        <div class="card bg-danger text-white">
                            <div class="card-body text-center">
                                <i class="fas fa-store fa-3x mb-3"></i>
                                <h4><?php echo isset($config) && isset($config['aberto']) && $config['aberto'] ? 'Aberto' : 'Fechado'; ?></h4>
                                <p>Status</p>
                            </div>
                            <a href="pages/configuracoes_empresa.php" class="card-footer bg-danger-dark text-white text-decoration-none">
                                <small>Configurar <i class="fas fa-arrow-circle-right"></i></small>
                            </a>
                        </div>
                    </div>
                </div>
                
                <?php if (isset($config)): ?>
                <div class="row">
                    <div class="col-12">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Configurações da Empresa</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Nome da Empresa:</strong> <?php echo $config['nome_empresa']; ?></p>
                                        <p><strong>Horário de Funcionamento:</strong> 
                                            <?php 
                                            if (isset($config['horario_inicio']) && $config['horario_inicio']) {
                                                echo date('H:i', strtotime($config['horario_inicio']));
                                            } else {
                                                echo "Não definido";
                                            }
                                            ?> às 
                                            <?php 
                                            if (isset($config['horario_fim']) && $config['horario_fim']) {
                                                echo date('H:i', strtotime($config['horario_fim']));
                                            } else {
                                                echo "Não definido";
                                            }
                                            ?>
                                        </p>
                                        <p><strong>Status Atual:</strong> 
                                            <span class="badge <?php echo isset($config['aberto']) && $config['aberto'] ? 'bg-success' : 'bg-danger'; ?>">
                                                <?php echo isset($config['aberto']) && $config['aberto'] ? 'Aberto' : 'Fechado'; ?>
                                            </span>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Sobre a Empresa:</strong></p>
                                        <p><?php echo isset($config['sobre_empresa']) ? $config['sobre_empresa'] : 'Sem descrição'; ?></p>
                                    </div>
                                </div>
                                <div class="text-center mt-3">
                                    <a href="pages/configuracoes_empresa.php" class="btn btn-primary">
                                        <i class="fas fa-edit"></i> Editar Configurações
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content');
            const sidebarCollapse = document.getElementById('sidebarCollapse');

            function checkScreenSize() {
                if (window.innerWidth <= 768) {
                    sidebar.classList.add('active');
                    content.classList.add('active');
                } else {
                    sidebar.classList.remove('active');
                    content.classList.remove('active');
                }
            }

            checkScreenSize();

            window.addEventListener('resize', checkScreenSize);

            sidebarCollapse.addEventListener('click', function() {
                sidebar.classList.toggle('active');
                content.classList.toggle('active');
            });
        });
    </script>
</body>
</html>