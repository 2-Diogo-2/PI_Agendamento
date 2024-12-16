<?php
session_start();
require_once 'php/includes/db_connect.php';

$db = new Database();

// Verifica se o usuário está logado
if (!isset($_SESSION['id']) || $_SESSION['tipo_usuario'] !== 'gerente_banda') {
    header("Location: acesso_negado.php");
    exit();
}

// Captura as bandas do usuário logado
$usuario_id = $_SESSION['id'];
$stmt = $db->prepare("SELECT * FROM bandas WHERE usuario_id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$bandas = $result->fetch_all(MYSQLI_ASSOC);
?>

<?php include 'php/includes/header.php'; ?>

<main>
    <section class="form-container">
        <h2>Minhas Bandas</h2>
        <div class="gallery">
            <div class="gallery-container">
                <?php foreach ($bandas as $banda): ?>
                    <div class="gallery-item">
                        <h3><?php echo htmlspecialchars($banda['nome_banda']); ?></h3>
                        <p>Gênero: <?php echo htmlspecialchars($banda['genero']); ?></p>
                        <div class="gallery-actions">
                            <a href="editar_banda.php?id=<?php echo $banda['id']; ?>" class="btn-secondary">Editar</a>
                            <form method="POST" action="php/controllers/excluir_banda.php" style="display:inline;">
                                <input type="hidden" name="banda_id" value="<?php echo $banda['id']; ?>">
                                <button type="submit" onclick="return confirm('Tem certeza que deseja excluir esta banda?');" class="btn-secondary">Excluir</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <p><a href="cadastro_bandas.php" class="btn-primary">Cadastrar Nova Banda</a></p>
    </section>
</main>

<?php include 'php/includes/footer.php'; ?>
