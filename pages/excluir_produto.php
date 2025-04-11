<?php
include '../php/conexao/conection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $conn->begin_transaction();
    
    try {
        $sql = "SELECT caminho_imagem FROM produtos WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $produto = $resultado->fetch_assoc();
        
        $sql = "DELETE FROM produtos WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            if ($produto && $produto['caminho_imagem']) {
                $caminho_imagem = '../' . $produto['caminho_imagem'];
                if (file_exists($caminho_imagem)) {
                    unlink($caminho_imagem);
                }
            }
            $conn->commit();
            header('Location: produtos.php?success=2'); 
        } else {
            throw new Exception('Erro ao excluir produto');
        }
    } catch (Exception $e) {
        $conn->rollback();
        header('Location: produtos.php?error=1');
    }
} else {
    header('Location: produtos.php');
}

$conn->close();
?> 