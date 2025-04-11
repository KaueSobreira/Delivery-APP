<?php
include 'Conexao/Conection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomeProduto = $_POST['nomeProd'];
    $descricao = $_POST['descProd'];
    $preco = $_POST['preco'];
    $categoria_id = $_POST['categoria'];
    $emPromocao = isset($_POST['emPromocao']) ? 1 : 0;

    $target_dir = "../images/";
    $file_extension = strtolower(pathinfo($_FILES["imagem"]["name"], PATHINFO_EXTENSION));
    $new_filename = uniqid() . '.' . $file_extension;
    $target_file = $target_dir . $new_filename;

    $check = getimagesize($_FILES["imagem"]["tmp_name"]);
    if($check !== false) {
        if (move_uploaded_file($_FILES["imagem"]["tmp_name"], $target_file)) {
            $caminho_imagem = "images/" . $new_filename;
            
            $sql = "INSERT INTO produtos (nome_produto, descricao, preco, categoria_id, caminho_imagem, em_promocao) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssdisi", $nomeProduto, $descricao, $preco, $categoria_id, $caminho_imagem, $emPromocao);
            
            if ($stmt->execute()) {
                header("Location: ../pages/produtos.php?success=1");
                exit();
            } else {
                header("Location: ../pages/produtos.php?error=1");
                exit();
            }
            
            $stmt->close();
        } else {
            header("Location: ../pages/produtos.php?error=2");
            exit();
        }
    } else {
        header("Location: ../pages/produtos.php?error=3");
        exit();
    }
    
    $conn->close();
}
?>
