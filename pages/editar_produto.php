<?php
include '../php/conexao/conection.php';

// Verifica se o ID foi fornecido
if (!isset($_GET['id'])) {
    header('Location: produtos.php');
    exit();
}

$id = $_GET['id'];

$sql = "SELECT * FROM produtos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$produto = $resultado->fetch_assoc();

if (!$produto) {
    header('Location: produtos.php');
    exit();
}

$sqlCategorias = "SELECT * FROM categoria";
$categorias = $conn->query($sqlCategorias);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto - Hamburgueria Tudo de Bom</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Editar Produto</h4>
                    </div>
                    <div class="card-body">
                        <form action="../php/produtos/atualizar_produto.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?php echo $produto['id']; ?>">
                            
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome do Produto</label>
                                <input type="text" class="form-control" id="nome" name="nome" value="<?php echo htmlspecialchars($produto['nome_produto']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="preco" class="form-label">Preço</label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="number" class="form-control" id="preco" name="preco" step="0.01" value="<?php echo $produto['preco']; ?>" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="descricao" class="form-label">Descrição</label>
                                <textarea class="form-control" id="descricao" name="descricao" rows="3" required><?php echo htmlspecialchars($produto['descricao']); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="categoria" class="form-label">Categoria</label>
                                <select class="form-select" id="categoria" name="categoria_id" required>
                                    <?php while ($cat = $categorias->fetch_assoc()): ?>
                                        <option value="<?php echo $cat['id']; ?>" <?php echo ($cat['id'] == $produto['categoria_id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($cat['nome_categoria']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="emPromocao" name="em_promocao" value="1" <?php echo $produto['em_promocao'] ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="emPromocao">Produto em promoção</label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="imagem" class="form-label">Nova Imagem (opcional)</label>
                                <input type="file" class="form-control" id="imagem" name="imagem" accept="image/*">
                                <?php if ($produto['caminho_imagem']): ?>
                                    <div class="mt-2">
                                        <small class="text-muted">Imagem atual: <?php echo basename($produto['caminho_imagem']); ?></small>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="produtos.php" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Voltar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Salvar Alterações
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?> 