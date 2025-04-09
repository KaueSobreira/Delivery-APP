<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo - Hamburgueria Tudo de Bom</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
    <div class="wrapper">
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3>Hamburgueria</h3>
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
            </ul>
        </nav>
        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </nav>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h1 class="text-center mb-4">Bem-vindo ao Painel de Controle</h1>
                                <p class="text-center">Gerencie seu cardápio e produtos de forma fácil e intuitiva.</p>
                            </div>
                        </div>
                    </div>
                </div>
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