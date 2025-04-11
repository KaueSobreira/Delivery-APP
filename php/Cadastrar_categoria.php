<?php
include 'Conexao/Conection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomeCategoria = $_POST['NomeCategoria'];
    
    $sql = "INSERT INTO categoria (nome_categoria) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nomeCategoria);
    
    if ($stmt->execute()) {
        header("Location: ../pages/categoria.php?success=1");
        exit();
    } else {
        header("Location: ../pages/categoria.php?error=1");
        exit();
    }
    
    $stmt->close();
    $conn->close();
}
?>
