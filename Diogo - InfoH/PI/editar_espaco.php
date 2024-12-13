<?php
session_start();
require_once 'php/includes/db_connect.php';

$db = new Database();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Captura os dados do formulário
    $espaco_id = $_POST['espaco_id'];
    $nome_espaco = $_POST['nome_espaco'];
    $cidade = isset($_POST['cidade']) ? $_POST['cidade'] : ''; 
    $capacidade = $_POST['capacidade'];
    $tipo_ambiente = $_POST['tipo_ambiente'];
    $descricao = $_POST['descricao'];
    $email = $_POST['email'];
    $redes_sociais = $_POST['redes_sociais'];

    // Atualiza o espaço no banco de dados
    $stmt = $db->prepare("UPDATE espacos SET nome_espaco = ?, cidade = ?, capacidade = ?, tipo_ambiente = ?, descricao = ?, email = ?, redes_sociais = ? WHERE id = ?");
    $stmt->bind_param("ssissssi", $nome_espaco, $cidade, $capacidade, $tipo_ambiente, $descricao, $email, $redes_sociais, $espaco_id);
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

            <label for="cidade">Cidade:</label>
            <input type="text" name="cidade" id="cidade" value="<?php echo htmlspecialchars($espaco['cidade']); ?>" required>

            <label for="capacidade">Capacidade:</label>
            <input type="number" name="capacidade" id="capacidade" value="<?php echo htmlspecialchars($espaco['capacidade']); ?>" required>

            <label for="tipo_ambiente">Tipo de Ambiente:</label>
            <select name="tipo_ambiente" id="tipo_ambiente" required>
                <option value="interno" <?php echo ($espaco['tipo_ambiente'] == 'interno') ? 'selected' : ''; ?>>Interno</option>
                <option value="externo" <?php echo ($espaco['tipo_ambiente'] == 'externo') ? 'selected' : ''; ?>>Externo</option>
                <option value="misturado" <?php echo ($espaco['tipo_ambiente'] == 'misturado') ? 'selected' : ''; ?>>Misturado</option>
            </select>

            <label for="descricao">Descrição:</label>
            <textarea name="descricao" id="descricao" required><?php echo htmlspecialchars($espaco['descricao']); ?></textarea>

            <label for="email">Email para contato:</label>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($espaco['email']); ?>" required>

            <label for="redes_sociais">Redes Sociais:</label>
            <textarea name="redes_sociais" id="redes_sociais" required><?php echo htmlspecialchars($espaco['redes_sociais']); ?></textarea>

            <input type="submit" value="Atualizar Espaço">
        </form>
    </section>
</main>

<?php include 'php/includes/footer.php'; ?>