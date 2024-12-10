<?php
session_start();
require_once 'php/includes/db_connect.php';

$db = new Database();

// Verifica se o usuário está logado
if (!isset($_SESSION['id'])) {
    header("Location: acesso_negado.php");
    exit();
}

// Captura a solicitação a ser editada
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $solicitacao_id = $_POST['solicitacao_id'];
    $stmt = $db->prepare("SELECT * FROM solicitacoes_agendamento WHERE id = ?");
    $stmt->bind_param("i", $solicitacao_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $solicitacao = $result->fetch_assoc();

    if (!$solicitacao) {
        echo "<script>alert('Solicitação não encontrada.'); window.location.href = 'gerenciar_solicitacoes.php';</script>";
        exit();
    }
} else {
    header("Location: gerenciar_solicitacoes.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['atualizar'])) {
    $nova_data = $_POST['nova_data'];
    $nova_quantia = $_POST['nova_quantia'];

    $stmt = $db->prepare("UPDATE solicitacoes_agendamento SET nova_data = ?, nova_quantia = ? WHERE id = ?");
    $stmt->bind_param("ssi", $nova_data, $nova_quantia, $solicitacao_id);

    if ($stmt->execute()) {
        echo "<script>alert('Solicitação atualizada com sucesso!'); window.location.href = 'gerenciar_solicitacoes.php';</script>";
    } else {
        echo "<script>alert('Erro ao atualizar a solicitação.');</script>";
    }
}

$stmt->close();
$db->close();
?>

<?php include 'php/includes/header.php'; ?>

<main>
    <section class="form-container">
        <h2>Editar Solicitação</h2>
        <form method="post" action="">
            <input type="hidden" name="solicitacao_id" value="<?php echo $solicitacao['id']; ?>">
            <label for="nova_data">Nova Data:</label>
            <input type="datetime-local" name="nova_data" id="nova_data" value="<?php echo date('Y-m-d\TH:i', strtotime($solicitacao['data_agendamento'])); ?>" required>

            <label for="nova_quantia">Nova Quantia:</label>
            <input type=" number" name="nova_quantia" id="nova_quantia" value="<?php echo htmlspecialchars($solicitacao['quantia']); ?>" required>

            <button type="submit" name="atualizar">Atualizar Solicitação</button>
        </form>
    </section>
</main>

<?php include 'php/includes/footer.php'; ?>