<?php
include '../conexao/conection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $preco = $_POST['preco'];
    $descricao = $_POST['descricao'];
    $categoria_id = $_POST['categoria_id'];
    
    $conn->begin_transaction();
    
    try {
        $sql = "UPDATE produtos SET nome_produto = ?, preco = ?, descricao = ?, categoria_id = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdsii", $nome, $preco, $descricao, $categoria_id, $id);
        $stmt->execute();

        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
            $imagem = $_FILES['imagem'];
            $ext = strtolower(pathinfo($imagem['name'], PATHINFO_EXTENSION));
            
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
                throw new Exception('Tipo de arquivo não permitido');
            }
            
            $novo_nome = uniqid() . '.' . $ext;
            $caminho = '../../images/' . $novo_nome;
            
            if (move_uploaded_file($imagem['tmp_name'], $caminho)) {
                $sql = "SELECT caminho_imagem FROM produtos WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $resultado = $stmt->get_result();
                $produto = $resultado->fetch_assoc();
                
                if ($produto['caminho_imagem']) {
                    $imagem_antiga = '../../' . $produto['caminho_imagem'];
                    if (file_exists($imagem_antiga)) {
                        unlink($imagem_antiga);
                    }
                }
                
                $caminho_relativo = 'images/' . $novo_nome;
                $sql = "UPDATE produtos SET caminho_imagem = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $caminho_relativo, $id);
                $stmt->execute();
            } else {
                throw new Exception('Erro ao fazer upload da imagem');
            }
        }
        
        $conn->commit();
        header('Location: ../../pages/produtos.php?success=1');
        exit();
        
    } catch (Exception $e) {
        $conn->rollback();
        header('Location: ../../pages/produtos.php?error=1');
        exit();
    }
}

$conn->close();
?> 