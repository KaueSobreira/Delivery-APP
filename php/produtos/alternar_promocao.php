<?php
include_once '../conexao/conection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}

if (!isset($_POST['id']) || !isset($_POST['status'])) {
    echo json_encode(['success' => false, 'message' => 'Dados incompletos']);
    exit;
}

$id = intval($_POST['id']);
$status = intval($_POST['status']);

if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID inválido']);
    exit;
}

if ($status !== 0 && $status !== 1) {
    echo json_encode(['success' => false, 'message' => 'Status inválido']);
    exit;
}

try {
    $sql = "UPDATE produtos SET em_promocao = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $status, $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Status atualizado com sucesso']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao atualizar status: ' . $stmt->error]);
    }
    
    $stmt->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erro: ' . $e->getMessage()]);
}

$conn->close();
?> 