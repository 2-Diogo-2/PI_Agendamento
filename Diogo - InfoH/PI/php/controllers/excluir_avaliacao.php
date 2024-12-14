<?php
session_start();
require_once '../includes/db_connect.php';

$db = new Database();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $avaliacao_id = $_POST['avaliacao_id'];

    // Verifica se a avaliação pertence ao usuário logado
    $usuario_id = $_SESSION['id'];
    $stmt = $db->prepare("DELETE FROM avaliacoes WHERE id = ? AND usuario_id = ?");
    $stmt->bind_param("ii", $avaliacao_id, $usuario_id);
    $stmt->execute();

    // Redireciona de volta para a página de detalhes
    header("Location: ../../detalhes.php?id=" . $_POST['referencia_id'] . "&tipo=" . $_POST['tipo']);
    exit();
}
?>