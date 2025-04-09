<?php
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $banco = 'teste';

    $conn = mysqli_connect($host, $user, $password, $banco);   
    if (!$conn) {
        die("Conexão Falhou " . mysqli_connect_error());
    }
    // echo "Conexão Bem Sucedida";

?>