<?php
include '../php/conexao/conection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $conn->begin_transaction();
    
    try {
        $sql = "SELECT COUNT(*) as total FROM produtos WHERE categoria_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $total = $resultado->fetch_assoc()['total'];
        
        if ($total > 0) {
            header('Location: categoria.php?error=2');
            exit();
        }
        $sql = "DELETE FROM categoria WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $conn->commit();
            header('Location: categoria.php?success=2');
        } else {
            throw new Exception('Erro ao excluir categoria');
        }
    } catch (Exception $e) {
        $conn->rollback();
        header('Location: categoria.php?error=1');
    }
} else {
    header('Location: categoria.php');
}

$conn->close();
?> 