<?php
session_start();
require_once 'php/includes/db_connect.php';

$db = new Database();

// Verifica se o usuário está logado
if (!isset($_SESSION['id']) || $_SESSION['tipo_usuario'] !== 'espaco_apresentacao') {
    header("Location: acesso_negado.php");
    exit();
}

// Captura os espaços do usuário logado
$usuario_id = $_SESSION['id'];
$stmt = $db->prepare("SELECT * FROM espacos WHERE usuario_id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$espacos = $result->fetch_all(MYSQLI_ASSOC);
?>

<?php include 'php/includes/header.php'; ?>

<main>
    <section class="form-container">
        <h2>Meus Espaços</h2>
        <table>
            <thead>
                <tr>
                    <th>Nome do Espaço</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($espacos as $espaco): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($espaco['nome_espaco']); ?></td>
                        <td>
                            <a href="editar_espaco.php?id=<?php echo $espaco['id']; ?>">Editar</a>
                            <form method="POST" action="php/controllers/excluir_espaco.php" style="display:inline;">
                                <input type="hidden" name="espaco_id" value="<?php echo $espaco['id']; ?>">
                                <button type="submit" onclick="return confirm('Tem certeza que deseja excluir este espaço?');">Excluir</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</main>

<?php include 'php/includes/footer.php'; ?>