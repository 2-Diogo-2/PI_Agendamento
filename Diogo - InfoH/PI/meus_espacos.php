<?php
session_start();
require_once 'php/includes/db_connect.php';

$db = new Database();

// Verifica se o usuário está logado
if (!isset($_SESSION['id'])) {
    header("Location: acesso_negado.php");
    exit();
}

// Captura os espaços do usuário logado
$usuario_id = $_SESSION['id'];
$stmt = $db->prepare("SELECT id, nome_espaco FROM espacos WHERE usuario_id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$espacos = $result->fetch_all(MYSQLI_ASSOC);
?>

<?php include 'php/includes/header.php'; ?>

<main>
    <section class="form-container">
        <h2>Meus Espaços</h2>
        <div class="gallery">
            <div class="gallery-container">
                <?php if (count($espacos) > 0): ?>
                    <?php foreach ($espacos as $espaco): ?>
                        <div class="gallery-item">
                            <h3><?php echo htmlspecialchars($espaco['nome_espaco']); ?></h3>
                            <div class="gallery-actions">
                                <a href="editar_espaco.php?id=<?php echo $espaco['id']; ?>" class="btn-secondary">Editar</a>
                                <form method="POST" action="php/controllers/deletar_espaco.php" style="display:inline;">
                                    <input type="hidden" name="espaco_id" value="<?php echo $espaco['id']; ?>">
                                    <button type="submit" onclick="return confirm('Tem certeza que deseja excluir este espaço?');" class="btn-secondary">Excluir</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Você ainda não cadastrou nenhum espaço.</p>
                <?php endif; ?>
            </div>
        </div>
        <p><a href="cadastro_espacos.php" class="btn-primary">Cadastrar Novo Espaço</a></p>
    </section>
</main>

<?php include 'php/includes/footer.php'; ?>
