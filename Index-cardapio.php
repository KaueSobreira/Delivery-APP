<?php 
    include('php/conexao/conection.php');

    $sqlCategorias = 'SELECT * FROM categoria';
    $resultCategorias = $conn->query($sqlCategorias);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hamburgueria Tudo de Bom - Cardápio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <h1 class="text-center mb-5">Cardápio</h1>
        
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</body>
</html>
