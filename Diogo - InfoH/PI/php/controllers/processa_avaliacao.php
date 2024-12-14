<?php
session_start();
require_once '../includes/db_connect.php';

$db = new Database();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $referencia_id = $_POST['referencia_id'];
    $tipo = $_POST['tipo'];
    $usuario_id = $_SESSION['id'];
    $nota = $_POST['nota'];
    $comentario = $_POST['comentario'];

    // Insere a avaliação no banco de dados
    $stmt = $db->prepare("INSERT INTO avaliacoes (referencia_id, tipo, usuario_id, nota, comentario) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isiss", $referencia_id, $tipo, $usuario_id, $nota, $comentario);
    
    if ($stmt->execute()) {
        // Armazena a mensagem de sucesso na sessão
        $_SESSION['success_message'] = "Avaliação enviada com sucesso!";
    } else {
        $_SESSION['error_message'] = "Erro ao enviar a avaliação.";
    }
    $stmt->close();
    $db->close();

    // Redireciona de volta para a página de detalhes
    header("Location: ../../detalhes.php?id=$referencia_id&tipo=$tipo");
    exit();
}
?>