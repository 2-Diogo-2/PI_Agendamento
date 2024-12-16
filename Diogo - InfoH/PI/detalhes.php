<?php
session_start();
require_once 'php/includes/db_connect.php';

$db = new Database();

// Captura o ID e tipo (banda ou espaço) da URL
$id = $_GET['id'];
$tipo = $_GET['tipo']; // 'banda' ou 'espaco'

// Busca os detalhes da banda ou espaço
if ($tipo === 'banda') {
    $stmt = $db->prepare("SELECT * FROM bandas WHERE id = ?");
} else {
    $stmt = $db->prepare("SELECT * FROM espacos WHERE id = ?");
}
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$detalhes = $result->fetch_assoc();

// Busca as avaliações
$stmt = $db->prepare("SELECT a.*, u.nome FROM avaliacoes a JOIN usuarios u ON a.usuario_id = u.id WHERE a.referencia_id = ? AND a.tipo = ?");
$stmt->bind_param("is", $id, $tipo);
$stmt->execute();
$result = $stmt->get_result();
$avaliacoes = $result->fetch_all(MYSQLI_ASSOC);
?>

<?php include 'php/includes/header.php'; ?>

<main>
<div class="container">
    <section class="details-header custom-header" style="text-align: center;">
        <h1><?php echo htmlspecialchars($detalhes['nome_banda'] ?? $detalhes['nome_espaco']); ?></h1>
        <p class="description"><?php echo htmlspecialchars($detalhes['descricao']); ?></p>
    </section>

    <section class="details-info">
        <div class="contact-info">
            <h2>Informações de Contato</h2>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($detalhes['email']); ?></p>
            <p><strong>Redes Sociais:</strong> <?php echo htmlspecialchars($detalhes['redes_sociais']); ?></p>
        </div>

        <div class="reviews">
            <h2>Avaliações</h2>
            <ul>
                <?php foreach ($avaliacoes as $avaliacao): ?>
                    <li>
                        <strong><?php echo htmlspecialchars($avaliacao['nome']); ?></strong> (<?php echo htmlspecialchars($avaliacao['nota']); ?> estrelas)
                        <p><?php echo htmlspecialchars($avaliacao['comentario']); ?></p>
                        <?php if (isset($_SESSION['id']) && $_SESSION['id'] == $avaliacao['usuario_id']): ?>
                            <form method="POST" action="php/controllers/excluir_avaliacao.php" style="display:inline;">
                                <input type="hidden" name="avaliacao_id" value="<?php echo $avaliacao['id']; ?>">
                                <input type="hidden" name="referencia_id" value="<?php echo $id; ?>">
                                <input type="hidden" name="tipo" value="<?php echo $tipo; ?>">
                                <button type="submit" onclick="return confirm('Tem certeza que deseja excluir esta avaliação?');">Excluir</button>
                            </form>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <?php if (isset($_SESSION['success_message'])): ?>
            <p class="success"><?php echo $_SESSION['success_message']; ?></p>
            <?php unset($_SESSION['success_message']); // Limpa a mensagem após exibi-la ?>
        <?php elseif (isset($_SESSION['error_message'])): ?>
            <p class="error"><?php echo $_SESSION['error_message']; ?></p>
            <?php unset($_SESSION['error_message']); // Limpa a mensagem após exibi-la ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['id']) && $_SESSION['tipo_usuario'] === 'usuario_comum'): ?>
            <div class="review-form">
                <h2>Deixe sua Avaliação</h2>
                <form method="POST" action="php/controllers/processa_avaliacao.php">
                    <input type="hidden" name="referencia_id" value="<?php echo $id; ?>">
                    <input type="hidden" name="tipo" value="<?php echo $tipo; ?>">
                    <label for="nota">Nota:</label>
                    <select name="nota" id="nota" required>
                        <option value="1">1 estrela</option>
                        <option value="2">2 estrelas</option>
                        <option value="3">3 estrelas</option>
                        <option value="4">4 estrelas</option>
                        <option value="5">5 estrelas</option>
                    </select>
                    <label for="comentario">Comentário:</label>
                    <textarea name="comentario" id="comentario" required></textarea>
                    <input type="submit" value="Enviar Avaliação">
                </form>
            </div>
        <?php else: ?>
            <p>Você precisa ser um usuário comum para deixar uma avaliação.</p>
        <?php endif; ?>
    </section>
</div>
</main>

<?php include 'php/includes/footer.php'; ?>