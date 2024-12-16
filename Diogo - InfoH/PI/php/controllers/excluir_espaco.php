// php/controllers/excluir_espaco.php
<?php
session_start();
require_once '../includes/db_connect.php';

$db = new Database();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $espaco_id = $_POST['espaco_id'];

    // Verifica se o espaço pertence ao usuário logado
    $usuario_id = $_SESSION['id'];
    $stmt = $db->prepare("DELETE FROM espacos WHERE id = ? AND usuario_id = ?");
    $stmt->bind_param("ii", $espaco_id, $usuario_id);
    $stmt->execute();

    header("Location: ../../meus_espacos.php");
    exit();
}
?>