<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Produto - Hamburgueria Tudo de Bom</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="text-center mb-4">Cadastrar Produto</h2>
                        
                        <form enctype="multipart/form-data" method="post" action="../php/Cadastrar_produto.php" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label for="nomeProd" class="form-label">Nome do Produto</label>
                                <input type="text" class="form-control" name="nomeProd" id="nomeProd" required>
                            </div>
                            <div class="mb-3">
                                <label for="descProd" class="form-label">Descrição:</label>
                                <textarea class="form-control" name="descProd" id="descProd" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="preco" class="form-label">Preço</label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="text" class="form-control" name="preco" id="preco" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="imagem" class="form-label">Imagem do Produto</label>
                                <input type="file" class="form-control" name="imagem" id="imagem" accept="image/*" required>
                            </div>

                            <div class="mb-4">
                                <label for="categoria" class="form-label">Categoria</label>
                                <select class="form-select" name="categoria" id="categoria" required>
                                    <option value="" selected disabled>Selecione uma categoria</option>
                                    <?php 
                                        include('../php/conexao/conection.php');

                                        $sql = 'SELECT * FROM categoria';
                                        $result = $conn->query($sql);

                                        if ($result && $result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<option value='" . $row['id'] . "'>" . $row['nome_categoria'] . "</option>";
                                            }
                                        } else {
                                            echo "<option value=''>Nenhuma categoria encontrada</option>";
                                        }

                                        $conn->close();
                                    ?>
                                </select>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">Cadastrar Produto</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()

        document.getElementById('preco').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = (value/100).toFixed(2);
            e.target.value = value;
        });
    </script>
</body>
</html>