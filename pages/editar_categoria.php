<?php
include '../php/conexao/conection.php';

if (!isset($_GET['id'])) {
    header('Location: categoria.php');
    exit();
}

$id = $_GET['id'];

$sql = "SELECT * FROM categoria WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$categoria = $resultado->fetch_assoc();

if (!$categoria) {
    header('Location: categoria.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Categoria - Hamburgueria Tudo de Bom</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Editar Categoria</h4>
                    </div>
                    <div class="card-body">
                        <form action="../php/categorias/atualizar_categoria.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo $categoria['id']; ?>">
                            
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome da Categoria</label>
                                <input type="text" class="form-control" id="nome" name="nome_categoria" 
                                       value="<?php echo htmlspecialchars($categoria['nome_categoria']); ?>" required>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="categoria.php" class="btn btn-secondary">
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