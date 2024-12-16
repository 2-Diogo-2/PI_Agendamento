<?php
session_start();
require_once 'php/includes/db_connect.php';

$db = new Database();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Captura os dados do formulário
    $banda_id = $_POST['banda_id'];
    $nome_banda = $_POST['nome_banda'];
    $genero = $_POST['genero'];
    $descricao = $_POST['descricao'];
    // Adicione outros campos conforme necessário

    // Atualiza a banda no banco de dados
    $stmt = $db->prepare("UPDATE bandas SET nome_banda = ?, genero = ?, descricao = ? WHERE id = ?");
    $stmt->bind_param("sssi", $nome_banda, $genero, $descricao, $banda_id);
    $stmt->execute();

    header("Location: minhas_bandas.php");
    exit();
}

// Captura os dados da banda para edição
$banda_id = $_GET['id'];
$stmt = $db->prepare("SELECT * FROM bandas WHERE id = ?");
$stmt->bind_param("i", $banda_id);
$stmt->execute();
$result = $stmt->get_result();
$banda = $result->fetch_assoc();
?>

<?php include 'php/includes/header.php'; ?>

<main>
    <section class="form-container">
        <h2>Editar Banda</h2>
        <form method="post" action="">
            <input type="hidden" name="banda_id" value="<?php echo $banda['id']; ?>">
            <label for="nome_banda">Nome da Banda:</label>
            <input type="text" name="nome_banda" id="nome_banda" value="<?php echo htmlspecialchars($banda['nome_banda']); ?>" required>

            <label for="genero">Gênero Musical:</label>
            <input type="text" name="genero" id="genero" value="<?php echo htmlspecialchars($banda['genero']); ?>" required>

            <label for="descricao">Descrição:</label>
            <textarea name="descricao" id="descricao" required><?php echo htmlspecialchars($banda['descricao']); ?></textarea>

            <input type="submit" value="Atualizar Banda">
        </form>
    </section>
</main>

<?php include 'php/includes/footer.php'; ?>