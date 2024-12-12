<?php
session_start();
require_once 'php/includes/db_connect.php';

$db = new Database();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Captura os dados do formulário
    $espaco_id = $_POST['espaco_id'];
    $nome_espaco = $_POST['nome_espaco'];
    $localizacao = $_POST['localizacao'];
    // Adicione outros campos conforme necessário

    // Atualiza o espaço no banco de dados
    $stmt = $db->prepare("UPDATE espacos SET nome_espaco = ?, localizacao = ? WHERE id = ?");
    $stmt->bind_param("ssi", $nome_espaco, $localizacao, $espaco_id);
    $stmt->execute();

    header("Location: meus_espacos.php");
    exit();
}

// Captura os dados do espaço para edição
$espaco_id = $_GET['id'];
$stmt = $db->prepare("SELECT * FROM espacos WHERE id = ?");
$stmt->bind_param("i", $espaco_id);
$stmt->execute();
$result = $stmt->get_result();
$espaco = $result->fetch_assoc();
?>

<?php include 'php/includes/header.php'; ?>

<main>
    <section class="form-container">
        <h2>Editar Espaço</h2>
        <form method="post" action="">
            <input type="hidden" name="espaco_id" value="<?php echo $espaco['id']; ?>">
            <label for="nome_espaco">Nome do Espaço:</label>
            <input type="text" name="nome_espaco" id="nome_espaco" value="<?php echo htmlspecialchars($espaco['nome_espaco']); ?>" required>
            <label for="localizacao">Localização:</label>
            <input type="text" name="localizacao" id="localizacao" value="<?php echo htmlspecialchars($espaco['localizacao']); ?>" required>
            <input type="submit" value="Atualizar Espaço">
        </form>
    </section>
</main>

<?php include 'php/includes/footer.php'; ?>