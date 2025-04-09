<?php 
    include('php/conexao/conection.php');

    // Buscar configurações da empresa
    $sqlConfig = "SELECT * FROM configuracoes_empresa LIMIT 1";
    $resultConfig = $conn->query($sqlConfig);
    
    if ($resultConfig && $resultConfig->num_rows > 0) {
        $config = $resultConfig->fetch_assoc();
    } else {
        // Valores padrão caso não exista configurações
        $config = [
            'nome_empresa' => 'SPACE BURGER',
            'sobre_empresa' => 'A melhor hamburgueria da cidade!',
            'titulo_banner' => 'UM UNIVERSO DE<br><span>SABORES</span>'
        ];
    }
    
    // Obter dia da semana atual (0 para domingo, 6 para sábado)
    $diaSemanaAtual = date('w');
    
    // Buscar informações do horário de hoje
    $sqlHorarioHoje = "SELECT * FROM horarios_funcionamento WHERE dia_semana = $diaSemanaAtual LIMIT 1";
    $resultHorarioHoje = $conn->query($sqlHorarioHoje);
    
    if ($resultHorarioHoje && $resultHorarioHoje->num_rows > 0) {
        $horarioHoje = $resultHorarioHoje->fetch_assoc();
    } else {
        // Valores padrão
        $horarioHoje = [
            'horario_inicio' => '10:00:00',
            'horario_fim' => '22:00:00',
            'aberto' => 1
        ];
    }
    
    // Verificar se o estabelecimento está aberto hoje
    $aberto = $horarioHoje['aberto'] == 1;
    
    // Buscar todos os horários para a página "Sobre"
    $sqlHorarios = "SELECT * FROM horarios_funcionamento ORDER BY dia_semana";
    $resultHorarios = $conn->query($sqlHorarios);
    $horarios = [];
    
    if ($resultHorarios && $resultHorarios->num_rows > 0) {
        while ($row = $resultHorarios->fetch_assoc()) {
            $horarios[$row['dia_semana']] = $row;
        }
    }
    
    // Array com os nomes dos dias da semana
    $diasSemana = [
        0 => 'Domingo',
        1 => 'Segunda-feira',
        2 => 'Terça-feira',
        3 => 'Quarta-feira',
        4 => 'Quinta-feira',
        5 => 'Sexta-feira',
        6 => 'Sábado'
    ];

    $sqlCategorias = 'SELECT * FROM categoria';
    $resultCategorias = $conn->query($sqlCategorias);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $config['nome_empresa']; ?> - Cardápio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/styles.css">
    <script src="https://code.iconify.design/2/2.2.1/iconify.min.js"></script>
</head>
<body class="bg-light">
    <div class="navbar">
        <div class="content">
            <a href="#" id="logo"><?php echo $config['nome_empresa']; ?></a>
            <div class="items">
                <a href="#about">Sobre</a>
                <a href="#cardapio">Cardápio</a>
                <a href="#promotions">Promoções</a>
                <a href="#service">Atendimento</a>
                <a href="./view/cart.html" class="cart-icon">
                    <span class="iconify-inline" data-icon="mdi:cart-variant"></span>
                </a>
                <a href="#" class="whatsapp-icon">
                    <span class="iconify-inline" data-icon="akar-icons:whatsapp-fill"></span>
                </a>
            </div>
            <div class="btnMobile">
                <span class="iconify-inline" data-icon="mdi:menu"></span>
            </div>
        </div>
    </div>
    <div class="banner">
        <div class="bannerContent">
            <div>
                <h1><?php echo $config['titulo_banner']; ?></h1>
                <p id="subtitle"><?php echo $config['sobre_empresa']; ?></p>
                <a href="#menu" class="btn">Cardápio</a>
                <div class="social">
                    <a href="#">
                        <span class="iconify" data-icon="akar-icons:facebook-fill"></span>
                    </a>
                    <a href="#">
                        <span class="iconify" data-icon="akar-icons:instagram-fill"></span>
                    </a>
                    <a href="#">
                        <span class="iconify" data-icon="akar-icons:twitter-fill"></span>
                    </a>
                </div>
            </div>
            <img src="images/burger.png" alt="Imagem de um lanche">
        </div>
    </div>  
    
    <?php if (!$aberto): ?>
    <div class="alert alert-warning text-center py-3 mb-0" id="service">
        <h4><i class="fas fa-clock"></i> ESTABELECIMENTO FECHADO HOJE (<?php echo $diasSemana[$diaSemanaAtual]; ?>)</h4>
        <?php if ($horarioHoje['horario_inicio'] && $horarioHoje['horario_fim']): ?>
            <p class="mb-0">Horário normal de funcionamento: <?php echo date('H:i', strtotime($horarioHoje['horario_inicio'])); ?> às <?php echo date('H:i', strtotime($horarioHoje['horario_fim'])); ?></p>
        <?php else: ?>
            <p class="mb-0">Não abrimos <?php echo $diasSemana[$diaSemanaAtual]; ?></p>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    
    <div class="container py-5" id="about">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="text-center mb-4">Sobre Nós</h2>
                        <p class="lead"><?php echo $config['sobre_empresa']; ?></p>
                        
                        <h4 class="text-center mt-4 mb-3">Horários de Funcionamento</h4>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Dia</th>
                                        <th>Horário</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($horarios as $dia => $horario): ?>
                                        <tr <?php echo $dia == $diaSemanaAtual ? 'class="table-active"' : ''; ?>>
                                            <td><?php echo $diasSemana[$dia]; ?></td>
                                            <td>
                                                <?php if ($horario['aberto']): ?>
                                                    <?php echo date('H:i', strtotime($horario['horario_inicio'])); ?> às 
                                                    <?php echo date('H:i', strtotime($horario['horario_fim'])); ?>
                                                <?php else: ?>
                                                    Fechado
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($horario['aberto']): ?>
                                                    <span class="badge bg-success">Aberto</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Fechado</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="text-center mt-3">
                            <div class="d-inline-block border rounded p-3 bg-light">
                                <h5>Hoje (<?php echo $diasSemana[$diaSemanaAtual]; ?>)</h5>
                                <p class="mb-1">
                                    <?php if ($aberto): ?>
                                        <span class="badge bg-success p-2 mb-2">ABERTO AGORA</span><br>
                                        <?php echo date('H:i', strtotime($horarioHoje['horario_inicio'])); ?> às <?php echo date('H:i', strtotime($horarioHoje['horario_fim'])); ?>
                                    <?php else: ?>
                                        <span class="badge bg-danger p-2 mb-2">FECHADO HOJE</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <h1 class="text-center mb-5" id="cardapio">Cardápio</h1>
        
        <div class="row justify-content-center">
            <?php 
                if ($resultCategorias && $resultCategorias->num_rows > 0) {
                    while ($cat = $resultCategorias->fetch_assoc()) {
                        echo "<div class='categoria-wrapper'>";
                        echo "<div class='categoria-card' data-bs-toggle='collapse' data-bs-target='#produtos-" . $cat['id'] . "'>";
                        echo "<div class='card-body text-center'>";
                        echo "<h3 class='card-title'>" . $cat['nome_categoria'] . "</h3>";
                        echo "</div>";
                        echo "</div>";
                        
                        echo "<div class='collapse' id='produtos-" . $cat['id'] . "'>";
                        echo "<div class='row mt-3'>";
                        
                        $categoria_id = $cat['id'];
                        $sqlProdutos = "SELECT * FROM produtos WHERE categoria_id = $categoria_id";
                        $resultProdutos = $conn->query($sqlProdutos);

                        if ($resultProdutos && $resultProdutos->num_rows > 0) {
                            while ($prod = $resultProdutos->fetch_assoc()) {
                                $caminho_imagem = $prod['caminho_imagem'];
                                $imagem_path = 'images/' . basename($caminho_imagem);
                                
                                echo "<div class='col-md-6 mb-3'>";
                                echo "<div class='card produto-card'>";
                                
                                if (file_exists($imagem_path)) {
                                    echo "<img src='" . $imagem_path . "' class='card-img-top' alt='" . $prod['nome_produto'] . "'>";
                                } else {
                                    echo "<div class='card-img-top bg-light d-flex align-items-center justify-content-center' style='height: 200px;'>";
                                    echo "<i class='fas fa-image text-muted' style='font-size: 3rem;'></i>";
                                    echo "</div>";
                                }
                                
                                echo "<div class='card-body'>";
                                echo "<h5 class='card-title'>" . $prod['nome_produto'] . "</h5>";
                                echo "<p class='card-text'>" . $prod['descricao'] . "</p>";
                                echo "<p class='card-text text-end preco'>R$ " . number_format($prod['preco'], 2, ',', '.') . "</p>";
                                echo "</div>";
                                echo "</div>";
                                echo "</div>";
                            }
                        } else {
                            echo "<div class='col-12'>";
                            echo "<p class='text-center'>Nenhum produto nessa categoria.</p>";
                            echo "</div>";
                        }
                        
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<div class='col-12'>";
                    echo "<p class='text-center'>Nenhuma categoria encontrada.</p>";
                    echo "</div>";
                }

                $conn->close();
            ?>
        </div>
    </div>

    <div class="container py-5" id="promotions">
        <h1 class="text-center mb-5">Promoções</h1>
        
        <div class="row">
            <?php 
                include('php/conexao/conection.php');
                
                $sqlPromocoes = "SELECT p.*, c.nome_categoria FROM produtos p 
                               JOIN categoria c ON p.categoria_id = c.id 
                               WHERE p.em_promocao = 1";
                $resultPromocoes = $conn->query($sqlPromocoes);
                
                if ($resultPromocoes && $resultPromocoes->num_rows > 0) {
                    while ($prod = $resultPromocoes->fetch_assoc()) {
                        $caminho_imagem = $prod['caminho_imagem'];
                        
                        echo "<div class='col-md-4 mb-4'>";
                        echo "<div class='card produto-card promocao-card'>";
                        
                        if (file_exists($caminho_imagem)) {
                            echo "<img src='" . $caminho_imagem . "' class='card-img-top' alt='" . $prod['nome_produto'] . "'>";
                        } else {
                            echo "<div class='card-img-top bg-light d-flex align-items-center justify-content-center' style='height: 200px;'>";
                            echo "<i class='fas fa-image text-muted' style='font-size: 3rem;'></i>";
                            echo "</div>";
                        }
                        
                        echo "<div class='card-body'>";
                        echo "<div class='promocao-badge'>PROMOÇÃO</div>";
                        echo "<h5 class='card-title'>" . $prod['nome_produto'] . "</h5>";
                        echo "<p class='card-text categoria-tag'>" . $prod['nome_categoria'] . "</p>";
                        echo "<p class='card-text'>" . $prod['descricao'] . "</p>";
                        echo "<p class='card-text text-end preco'>R$ " . number_format($prod['preco'], 2, ',', '.') . "</p>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<div class='col-12'>";
                    echo "<p class='text-center'>Nenhuma promoção disponível no momento.</p>";
                    echo "</div>";
                }
            ?>
        </div>
    </div>

    <div class="container py-5" id="menu">
        
        <div class="row justify-content-center">
            <?php 
                if ($resultCategorias && $resultCategorias->num_rows > 0) {
                    while ($cat = $resultCategorias->fetch_assoc()) {
                        echo "<div class='categoria-wrapper'>";
                        echo "<div class='categoria-card' data-bs-toggle='collapse' data-bs-target='#produtos-" . $cat['id'] . "'>";
                        echo "<div class='card-body text-center'>";
                        echo "<h3 class='card-title'>" . $cat['nome_categoria'] . "</h3>";
                        echo "</div>";
                        echo "</div>";
                        
                        echo "<div class='collapse' id='produtos-" . $cat['id'] . "'>";
                        echo "<div class='row mt-3'>";
                        
                        $categoria_id = $cat['id'];
                        $sqlProdutos = "SELECT * FROM produtos WHERE categoria_id = $categoria_id";
                        $resultProdutos = $conn->query($sqlProdutos);

                        if ($resultProdutos && $resultProdutos->num_rows > 0) {
                            while ($prod = $resultProdutos->fetch_assoc()) {
                                $caminho_imagem = $prod['caminho_imagem'];
                                $imagem_path = 'images/' . basename($caminho_imagem);
                                
                                echo "<div class='col-md-6 mb-3'>";
                                echo "<div class='card produto-card'>";
                                
                                if (file_exists($imagem_path)) {
                                    echo "<img src='" . $imagem_path . "' class='card-img-top' alt='" . $prod['nome_produto'] . "'>";
                                } else {
                                    echo "<div class='card-img-top bg-light d-flex align-items-center justify-content-center' style='height: 200px;'>";
                                    echo "<i class='fas fa-image text-muted' style='font-size: 3rem;'></i>";
                                    echo "</div>";
                                }
                                
                                echo "<div class='card-body'>";
                                echo "<h5 class='card-title'>" . $prod['nome_produto'] . "</h5>";
                                echo "<p class='card-text'>" . $prod['descricao'] . "</p>";
                                echo "<p class='card-text text-end preco'>R$ " . number_format($prod['preco'], 2, ',', '.') . "</p>";
                                echo "</div>";
                                echo "</div>";
                                echo "</div>";
                            }
                        } else {
                            echo "<div class='col-12'>";
                            echo "<p class='text-center'>Nenhum produto nessa categoria.</p>";
                            echo "</div>";
                        }
                        
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<div class='col-12'>";
                    echo "<p class='text-center'>Nenhuma categoria encontrada.</p>";
                    echo "</div>";
                }

                $conn->close();
            ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Menu mobile toggle
            const btnMobile = document.querySelector('.btnMobile');
            const navItems = document.querySelector('.items');
            
            if (btnMobile) {
                btnMobile.addEventListener('click', function() {
                    navItems.classList.toggle('active');
                });
            }
            
            // Fechar menu ao clicar em um link
            const navLinks = document.querySelectorAll('.items a');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    navItems.classList.remove('active');
                });
            });
            
            // Adicionar ícones através do Iconify (se disponível)
            if (window.Iconify) {
                Iconify.scan();
            }
            
            // Rolagem suave para as âncoras
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const targetId = this.getAttribute('href');
                    if (targetId === '#') return;
                    
                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        window.scrollTo({
                            top: targetElement.offsetTop - 70,
                            behavior: 'smooth'
                        });
                    }
                });
            });
        });
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</body>
</html>
