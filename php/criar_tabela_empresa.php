<?php
include 'conexao/conection.php';

// Verificar se a tabela configuracoes_empresa já existe
$checkTableEmpresa = "SHOW TABLES LIKE 'configuracoes_empresa'";
$tableEmpresaExists = $conn->query($checkTableEmpresa);

if ($tableEmpresaExists->num_rows == 0) {
    // Criar a tabela configuracoes_empresa se não existir
    $createTableEmpresa = "CREATE TABLE configuracoes_empresa (
        id INT(11) PRIMARY KEY AUTO_INCREMENT,
        nome_empresa VARCHAR(100) NOT NULL DEFAULT 'SPACE BURGER',
        sobre_empresa TEXT,
        titulo_banner VARCHAR(200) DEFAULT 'UM UNIVERSO DE SABORES',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    if ($conn->query($createTableEmpresa)) {
        // Inserir dados padrão
        $insertDefaultEmpresa = "INSERT INTO configuracoes_empresa (nome_empresa, sobre_empresa, titulo_banner) 
                          VALUES ('SPACE BURGER', 'A melhor hamburgueria da cidade!', 'UM UNIVERSO DE SABORES')";
        
        if ($conn->query($insertDefaultEmpresa)) {
            echo "<div class='alert alert-success'>Tabela configuracoes_empresa criada e dados padrão inseridos com sucesso!</div>";
        } else {
            echo "<div class='alert alert-warning'>Tabela criada, mas erro ao inserir dados padrão: " . $conn->error . "</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Erro ao criar a tabela configuracoes_empresa: " . $conn->error . "</div>";
    }
} else {
    echo "<div class='alert alert-info'>A tabela configuracoes_empresa já existe no banco de dados.</div>";
}

// Verificar se a tabela horarios_funcionamento já existe
$checkTableHorarios = "SHOW TABLES LIKE 'horarios_funcionamento'";
$tableHorariosExists = $conn->query($checkTableHorarios);

if ($tableHorariosExists->num_rows == 0) {
    // Criar a tabela horarios_funcionamento se não existir
    $createTableHorarios = "CREATE TABLE horarios_funcionamento (
        id INT(11) PRIMARY KEY AUTO_INCREMENT,
        dia_semana TINYINT NOT NULL COMMENT '0-Domingo, 1-Segunda, 2-Terça, 3-Quarta, 4-Quinta, 5-Sexta, 6-Sábado',
        horario_inicio TIME,
        horario_fim TIME,
        aberto TINYINT(1) DEFAULT 1,
        empresa_id INT(11) DEFAULT 1,
        FOREIGN KEY (empresa_id) REFERENCES configuracoes_empresa(id) ON DELETE CASCADE
    )";
    
    if ($conn->query($createTableHorarios)) {
        // Inserir dados padrão para cada dia da semana
        $diasSemana = [
            0 => 'Domingo',
            1 => 'Segunda-feira',
            2 => 'Terça-feira',
            3 => 'Quarta-feira',
            4 => 'Quinta-feira',
            5 => 'Sexta-feira',
            6 => 'Sábado'
        ];
        
        $success = true;
        
        foreach ($diasSemana as $dia_semana => $nome_dia) {
            $horario_inicio = ($dia_semana < 5) ? '10:00:00' : '10:00:00'; // Todos às 10h
            $horario_fim = ($dia_semana < 5) ? '22:00:00' : '23:00:00';    // Fins de semana até mais tarde
            $aberto = ($dia_semana == 1) ? 0 : 1;  // Segunda-feira fechado como exemplo
            
            $insertDefaultHorario = "INSERT INTO horarios_funcionamento (dia_semana, horario_inicio, horario_fim, aberto, empresa_id) 
                            VALUES ($dia_semana, '$horario_inicio', '$horario_fim', $aberto, 1)";
            
            if (!$conn->query($insertDefaultHorario)) {
                $success = false;
                echo "<div class='alert alert-warning'>Erro ao inserir horário para $nome_dia: " . $conn->error . "</div>";
            }
        }
        
        if ($success) {
            echo "<div class='alert alert-success'>Tabela horarios_funcionamento criada e dados padrão inseridos com sucesso!</div>";
        } else {
            echo "<div class='alert alert-warning'>Alguns horários não foram inseridos corretamente.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Erro ao criar a tabela horarios_funcionamento: " . $conn->error . "</div>";
    }
} else {
    echo "<div class='alert alert-info'>A tabela horarios_funcionamento já existe no banco de dados.</div>";
}

$conn->close();
?> 