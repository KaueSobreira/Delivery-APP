<?php
include('../php/conexao/conection.php');

// Verificar se a tabela configuracoes_empresa existe
$checkTable = "SHOW TABLES LIKE 'configuracoes_empresa'";
$tableExists = $conn->query($checkTable);

// Se a tabela não existir, redirecionar para o script de criação das tabelas
if ($tableExists->num_rows == 0) {
    header('Location: ../php/criar_tabela_empresa.php');
    exit();
}

// Dias da semana para exibição
$diasSemana = [
    0 => 'Domingo',
    1 => 'Segunda-feira',
    2 => 'Terça-feira',
    3 => 'Quarta-feira',
    4 => 'Quinta-feira',
    5 => 'Sexta-feira',
    6 => 'Sábado'
];

// Processar o formulário quando enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_empresa = $conn->real_escape_string($_POST['nome_empresa']);
    $sobre_empresa = $conn->real_escape_string($_POST['sobre_empresa']);
    $titulo_banner = $conn->real_escape_string($_POST['titulo_banner']);
    
    // Verificar se já existem registros
    $checkRecords = "SELECT COUNT(*) as total FROM configuracoes_empresa";
    $resultCheck = $conn->query($checkRecords);
    $rowCount = $resultCheck->fetch_assoc()['total'];
    
    if ($rowCount > 0) {
        // Atualizar o registro existente
        $sql = "UPDATE configuracoes_empresa SET 
                nome_empresa = '$nome_empresa',
                sobre_empresa = '$sobre_empresa',
                titulo_banner = '$titulo_banner'
                WHERE id = 1";
    } else {
        // Inserir novo registro
        $sql = "INSERT INTO configuracoes_empresa (nome_empresa, sobre_empresa, titulo_banner) 
                VALUES ('$nome_empresa', '$sobre_empresa', '$titulo_banner')";
    }
    
    $operacaoSucesso = $conn->query($sql);
    
    // Processar horários de funcionamento
    $horariosSucesso = true;
    
    for ($dia = 0; $dia <= 6; $dia++) {
        $aberto = isset($_POST["aberto_$dia"]) ? 1 : 0;
        $horario_inicio = $conn->real_escape_string($_POST["horario_inicio_$dia"]);
        $horario_fim = $conn->real_escape_string($_POST["horario_fim_$dia"]);
        
        // Verificar se já existe um registro para este dia
        $checkHorario = "SELECT id FROM horarios_funcionamento WHERE dia_semana = $dia";
        $resultHorario = $conn->query($checkHorario);
        
        if ($resultHorario && $resultHorario->num_rows > 0) {
            // Atualizar horário existente
            $horarioId = $resultHorario->fetch_assoc()['id'];
            $sqlHorario = "UPDATE horarios_funcionamento SET 
                          horario_inicio = '$horario_inicio',
                          horario_fim = '$horario_fim',
                          aberto = $aberto
                          WHERE id = $horarioId";
        } else {
            // Inserir novo horário
            $sqlHorario = "INSERT INTO horarios_funcionamento (dia_semana, horario_inicio, horario_fim, aberto, empresa_id) 
                          VALUES ($dia, '$horario_inicio', '$horario_fim', $aberto, 1)";
        }
        
        if (!$conn->query($sqlHorario)) {
            $horariosSucesso = false;
        }
    }
    
    if ($operacaoSucesso && $horariosSucesso) {
        $mensagem = "Configurações salvas com sucesso!";
        $tipo_mensagem = "success";
    } else {
        $mensagem = "Erro ao salvar: " . $conn->error;
        $tipo_mensagem = "danger";
    }
}

// Buscar dados do banco
$sql = "SELECT * FROM configuracoes_empresa LIMIT 1";
$result = $conn->query($sql);
$config = $result->fetch_assoc();

// Buscar horários de funcionamento
$sqlHorarios = "SELECT * FROM horarios_funcionamento ORDER BY dia_semana";
$resultHorarios = $conn->query($sqlHorarios);
$horarios = [];

if ($resultHorarios && $resultHorarios->num_rows > 0) {
    while ($row = $resultHorarios->fetch_assoc()) {
        $horarios[$row['dia_semana']] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações da Empresa - Hamburgueria Tudo de Bom</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <div class="navbar2">
        <div class="content">
            <a href="#" id="logo">SPACE BURGER</a>
            <div class="items">
                <a href="../index-cardapio.php">Voltar ao Site</a>
                <a href="../Index-painel.php">Painel Administrativo</a>
            </div>
            <div class="btnMobile">
                <span class="iconify-inline" data-icon="mdi:menu"></span>
            </div>
        </div>
    </div>
    
    <div class="container py-5 mt-5">
        <?php if (isset($mensagem)): ?>
            <div class="alert alert-<?php echo $tipo_mensagem; ?> alert-dismissible fade show" role="alert">
                <?php echo $mensagem; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
            </div>
        <?php endif; ?>
        
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h3 class="m-0">Configurações da Empresa</h3>
                    </div>
                    <div class="card-body config-form">
                        <form method="post" action="">
                            <div class="row mb-4">
                                <div class="col-md-6 mb-3">
                                    <label for="nome_empresa" class="form-label">Nome da Empresa</label>
                                    <input type="text" class="form-control" id="nome_empresa" name="nome_empresa" 
                                           value="<?php echo isset($config['nome_empresa']) ? $config['nome_empresa'] : ''; ?>">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="titulo_banner" class="form-label">Título do Banner</label>
                                    <input type="text" class="form-control" id="titulo_banner" name="titulo_banner" 
                                           value="<?php echo isset($config['titulo_banner']) ? $config['titulo_banner'] : ''; ?>">
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="sobre_empresa" class="form-label">Sobre a Empresa</label>
                                <textarea class="form-control" id="sobre_empresa" name="sobre_empresa" rows="4"><?php echo isset($config['sobre_empresa']) ? $config['sobre_empresa'] : ''; ?></textarea>
                            </div>
                            
                            <h4 class="mb-3">Horário de Funcionamento</h4>
                            <div class="row">
                                <div class="col-12 horarios-container">
                                    <?php for ($dia = 0; $dia <= 6; $dia++): ?>
                                        <?php 
                                            $horario = isset($horarios[$dia]) ? $horarios[$dia] : [
                                                'horario_inicio' => '10:00',
                                                'horario_fim' => '22:00',
                                                'aberto' => 1
                                            ];
                                            $aberto = isset($horario['aberto']) && $horario['aberto'] == 1;
                                        ?>
                                        <div class="horario-item <?php echo $aberto ? '' : 'fechado'; ?>" id="horario_<?php echo $dia; ?>">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="dia-semana"><?php echo $diasSemana[$dia]; ?></div>
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input toggle-status" type="checkbox" id="aberto_<?php echo $dia; ?>" 
                                                               name="aberto_<?php echo $dia; ?>" value="1" 
                                                               <?php echo $aberto ? 'checked' : ''; ?> 
                                                               data-dia="<?php echo $dia; ?>">
                                                        <label class="form-check-label" for="aberto_<?php echo $dia; ?>">
                                                            <span id="status_<?php echo $dia; ?>"><?php echo $aberto ? 'Aberto' : 'Fechado'; ?></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="row horarios-inputs">
                                                        <div class="col-6">
                                                            <label for="horario_inicio_<?php echo $dia; ?>" class="form-label">Horário de Abertura</label>
                                                            <input type="time" class="form-control" id="horario_inicio_<?php echo $dia; ?>" 
                                                                   name="horario_inicio_<?php echo $dia; ?>" 
                                                                   value="<?php echo isset($horario['horario_inicio']) ? $horario['horario_inicio'] : '10:00'; ?>">
                                                        </div>
                                                        <div class="col-6">
                                                            <label for="horario_fim_<?php echo $dia; ?>" class="form-label">Horário de Fechamento</label>
                                                            <input type="time" class="form-control" id="horario_fim_<?php echo $dia; ?>" 
                                                                   name="horario_fim_<?php echo $dia; ?>" 
                                                                   value="<?php echo isset($horario['horario_fim']) ? $horario['horario_fim'] : '22:00'; ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between mt-4">
                                <a href="../Index-painel.php" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Voltar
                                </a>
                                <button type="submit" class="btn btn-primary btn-save">
                                    <i class="fas fa-save me-2"></i>Salvar Configurações
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Prévia das configurações -->
                <div class="card shadow">
                    <div class="card-header">
                        <h4 class="m-0">Visualização</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="mb-3"><?php echo isset($config['nome_empresa']) ? $config['nome_empresa'] : 'SPACE BURGER'; ?></h5>
                                <p><strong>Sobre:</strong> <?php echo isset($config['sobre_empresa']) ? $config['sobre_empresa'] : 'Sem descrição'; ?></p>
                                
                                <p><strong>Horários de Funcionamento:</strong></p>
                                <ul class="list-group">
                                    <?php for ($dia = 0; $dia <= 6; $dia++): ?>
                                        <?php 
                                            $horario = isset($horarios[$dia]) ? $horarios[$dia] : [
                                                'horario_inicio' => '10:00',
                                                'horario_fim' => '22:00',
                                                'aberto' => 1
                                            ];
                                            $aberto = isset($horario['aberto']) && $horario['aberto'] == 1;
                                        ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><?php echo $diasSemana[$dia]; ?></span>
                                            <?php if ($aberto): ?>
                                                <span>
                                                    <?php 
                                                        echo date('H:i', strtotime($horario['horario_inicio'])) . ' às ' . 
                                                             date('H:i', strtotime($horario['horario_fim'])); 
                                                    ?>
                                                </span>
                                                <span class="badge bg-success">Aberto</span>
                                            <?php else: ?>
                                                <span>&nbsp;</span>
                                                <span class="badge bg-danger">Fechado</span>
                                            <?php endif; ?>
                                        </li>
                                    <?php endfor; ?>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <div class="banner-preview p-3 bg-light border rounded text-center">
                                    <h3 class="text-danger">Banner Preview</h3>
                                    <h2><?php echo isset($config['titulo_banner']) ? $config['titulo_banner'] : 'UM UNIVERSO DE SABORES'; ?></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.iconify.design/2/2.2.1/iconify.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Controle do toggle de status (aberto/fechado) para cada dia
            const toggles = document.querySelectorAll('.toggle-status');
            
            toggles.forEach(toggle => {
                toggle.addEventListener('change', function() {
                    const dia = this.dataset.dia;
                    const statusEl = document.getElementById(`status_${dia}`);
                    const container = document.getElementById(`horario_${dia}`);
                    
                    if (this.checked) {
                        statusEl.textContent = 'Aberto';
                        container.classList.remove('fechado');
                    } else {
                        statusEl.textContent = 'Fechado';
                        container.classList.add('fechado');
                    }
                });
            });
            
            // Menu mobile
            const btnMobile = document.querySelector('.btnMobile');
            const navItems = document.querySelector('.items');
            
            if (btnMobile) {
                btnMobile.addEventListener('click', function() {
                    navItems.classList.toggle('active');
                });
            }
        });
    </script>
</body>
</html> 