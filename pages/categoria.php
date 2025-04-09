<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorias - Hamburgueria Tudo de Bom</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Lista de Categorias</h2>
            <a href="Cadastrar_Categoria.html" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nova Categoria
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                include '../php/conexao/conection.php';

                                $sql = "SELECT id, nome_categoria FROM categoria";

                                $resultado = mysqli_query($conn, $sql);

                                if (mysqli_num_rows($resultado) > 0) {
                                    while ($linha = mysqli_fetch_assoc($resultado)) {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($linha['nome_categoria']) . "</td>";
                                        echo "<td>";
                                        echo "<a href='editar_categoria.php?id=" . $linha['id'] . "' class='btn btn-sm btn-info me-1'><i class='fas fa-edit'></i></a>";
                                        echo "<button class='btn btn-sm btn-danger' onclick='confirmarExclusao(" . $linha['id'] . ")'><i class='fas fa-trash'></i></button>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='2' class='text-center'>Nenhuma categoria encontrada.</td></tr>";
                                }

                                mysqli_close($conn);
                            ?>
                        </tbody>
                    </table>
                    <br>
                    <a href="../Index-painel.php" class="btn btn-primary">
                        <i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Voltar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Exclusão</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Tem certeza que deseja excluir esta categoria?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Excluir</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function confirmarExclusao(id) {
            $('#confirmModal').modal('show');
            $('#confirmDelete').off('click').on('click', function() {
                window.location.href = 'excluir_categoria.php?id=' + id;
            });
        }

        $(document).ready(function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('success')) {
                showNotification('Categoria cadastrada com sucesso!', 'success');
            } else if (urlParams.has('error')) {
                showNotification('Erro ao cadastrar categoria. Tente novamente.', 'danger');
            }
        });

        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `alert alert-${type} notification`;
            notification.textContent = message;
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.remove();
            }, 3000);
        }
    </script>
</body>
</html>
