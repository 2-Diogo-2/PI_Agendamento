<?php
session_start();
require_once 'php/includes/db_connect.php';

$db = new Database();

// Verifica se o usuário está logado e é um espaço de apresentação
if (!isset($_SESSION['id']) || $_SESSION['tipo_usuario'] !== 'espaco_apresentacao') {
    header("Location: acesso_negado.php");
    exit();
}

// Captura as bandas disponíveis
$stmt = $db->prepare("SELECT id, nome_banda FROM bandas");
$stmt->execute();
$result = $stmt->get_result();
$bandas = $result->fetch_all(MYSQLI_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $banda_id = $_POST['banda_id'];
    $data_agendamento = $_POST['data_agendamento'];

    // Insere a solicitação de agendamento
    $stmt = $db->prepare("INSERT INTO solicitacoes_agendamento (banda_id, espaco_id, data_agendamento) VALUES (?, ?, ?)");
    $espaco_id = $_SESSION['id']; // ID do espaço de apresentação logado
    $stmt->bind_param("iis", $banda_id, $espaco_id, $data_agendamento);

    if ($stmt->execute()) {
        echo "<script>alert('Solicitação de show enviada com sucesso!');</script>";
    } else {
        echo "<script>alert('Erro ao enviar solicitação. Tente novamente.');</script>";
    }
}
?>

<?php include 'php/includes/header.php'; ?>

<main>
    <section class="form-container">
        <h2>Solicitar Show</h2>
        <form method="post" action="">
            <label for="banda_id">Selecione a Banda:</label>
            <select name="banda_id" id="banda_id" required>
                <?php foreach ($bandas as $banda): ?>
                    <option value="<?php echo $banda['id']; ?>"><?php echo $banda['nome_banda']; ?></option>
                <?php endforeach; ?>
            </select>

            <label for="data_agendamento">Data do Agendamento:</label>
            <input type="datetime-local" name="data_agendamento" id="data_agendamento" required>

            <input type="submit" value="Solicitar Show">
        </form>
    </section>
</main>

<?php include 'php/includes/footer.php'; ?>