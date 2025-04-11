<?php
include '../conexao/conection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nome_categoria = $_POST['nome_categoria'];
    
    try {
        $sql = "UPDATE categoria SET nome_categoria = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $nome_categoria, $id);
        
        if ($stmt->execute()) {
            header('Location: ../../pages/categoria.php?success=1');
        } else {
            throw new Exception('Erro ao atualizar categoria');
        }
    } catch (Exception $e) {
        header('Location: ../../pages/categoria.php?error=1');
    }
}

$conn->close();
?> 