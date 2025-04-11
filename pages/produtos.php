<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos - Hamburgueria Tudo de Bom</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../styles/styles.css">
    <style>
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
            min-width: 300px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            animation: slideIn 0.3s forwards;
        }
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Lista de Produtos</h2>
            <a href="Cadastrar_produto.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Novo Produto
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Preço</th>
                                <th>Descrição</th>
                                <th>Categoria</th>
                                <th>Promoção</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                include '../php/conexao/conection.php';

                                $sql = "SELECT p.id, p.nome_produto, p.preco, p.descricao, c.nome_categoria, p.em_promocao 
                                        FROM produtos p
                                        JOIN categoria c ON p.categoria_id = c.id";

                                $resultado = mysqli_query($conn, $sql);

                                if (mysqli_num_rows($resultado) > 0) {
                                    while ($linha = mysqli_fetch_assoc($resultado)) {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($linha['nome_produto']) . "</td>";
                                        echo "<td>R$ " . number_format($linha['preco'], 2, ',', '.') . "</td>";
                                        echo "<td>" . htmlspecialchars($linha['descricao']) . "</td>";
                                        echo "<td>" . htmlspecialchars($linha['nome_categoria']) . "</td>";
                                        echo "<td>";
                                        $promocao_status = $linha['em_promocao'] ? 'ativo' : 'inativo';
                                        $promocao_cor = $linha['em_promocao'] ? 'success' : 'secondary';
                                        echo "<button class='btn btn-sm btn-" . $promocao_cor . " alternar-promocao' 
                                                data-id='" . $linha['id'] . "' 
                                                data-status='" . $promocao_status . "'>
                                                <i class='fas fa-tag'></i> " . ($linha['em_promocao'] ? 'Ativo' : 'Inativo') . "
                                              </button>";
                                        echo "</td>";
                                        echo "<td>";
                                        echo "<a href='editar_produto.php?id=" . $linha['id'] . "' class='btn btn-sm btn-info me-1'><i class='fas fa-edit'></i></a>";
                                        echo "<button class='btn btn-sm btn-danger' onclick='confirmarExclusao(" . $linha['id'] . ")'><i class='fas fa-trash'></i></button>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6' class='text-center'>Nenhum produto encontrado.</td></tr>";
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
                    Tem certeza que deseja excluir este produto?
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
                window.location.href = 'excluir_produto.php?id=' + id;
            });
        }

        $(document).ready(function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('success')) {
                showNotification('Produto cadastrado com sucesso!', 'success');
            } else if (urlParams.has('error')) {
                let errorMessage = 'Erro ao cadastrar produto. ';
                switch(urlParams.get('error')) {
                    case '1':
                        errorMessage += 'Erro no banco de dados.';
                        break;
                    case '2':
                        errorMessage += 'Erro ao fazer upload da imagem.';
                        break;
                    case '3':
                        errorMessage += 'Arquivo inválido. Por favor, envie uma imagem.';
                        break;
                    default:
                        errorMessage += 'Tente novamente.';
                }
                showNotification(errorMessage, 'danger');
            }

            $('.alternar-promocao').on('click', function() {
                const id = $(this).data('id');
                const currentStatus = $(this).data('status');
                const newStatus = currentStatus === 'ativo' ? 0 : 1;
                const button = $(this);
                
                $.ajax({
                    url: '../php/produtos/alternar_promocao.php',
                    type: 'POST',
                    data: { id: id, status: newStatus },
                    success: function(response) {
                        try {
                            const result = JSON.parse(response);
                            if (result.success) {
                                if (newStatus) {
                                    button.removeClass('btn-secondary').addClass('btn-success');
                                    button.html('<i class="fas fa-tag"></i> Ativo');
                                    button.data('status', 'ativo');
                                } else {
                                    button.removeClass('btn-success').addClass('btn-secondary');
                                    button.html('<i class="fas fa-tag"></i> Inativo');
                                    button.data('status', 'inativo');
                                }
                                showNotification('Status de promoção atualizado!', 'success');
                            } else {
                                showNotification('Erro ao atualizar status: ' + result.message, 'danger');
                            }
                        } catch (e) {
                            showNotification('Erro ao processar resposta', 'danger');
                        }
                    },
                    error: function() {
                        showNotification('Erro na comunicação com o servidor', 'danger');
                    }
                });
            });
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
