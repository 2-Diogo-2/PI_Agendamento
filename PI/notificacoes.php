<?php
session_start();
require_once 'php/includes/db_connect.php';

$db = new Database();

// Verifica se o usuário está logado
if (!isset($_SESSION['id'])) {
    header("Location: acesso_negado.php");
    exit();
}

// Captura o ID do usuário logado
$usuario_id = $_SESSION['id'];

// Captura as notificações do usuário
$stmt = $db->prepare("SELECT id, mensagem, data_criacao FROM notificacoes WHERE usuario_id = ? ORDER BY data_criacao DESC");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$notificacoes = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$db->close();
?>

<?php include 'php/includes/header.php'; ?>

<main>
    <section class="form-container">
        <h2>Notificações</h2>
        <?php if (empty($notificacoes)): ?>
            <p>Você não tem notificações.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($notificacoes as $notificacao): ?>
                    <li>
                        <strong><?php echo date('d/m/Y H:i', strtotime($notificacao['data_criacao'])); ?></strong>: 
                        <?php echo htmlspecialchars($notificacao['mensagem']); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </section>
</main>

<?php include 'php/includes/footer.php'; ?>