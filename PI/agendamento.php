<?php
session_start();
require_once 'php/includes/db_connect.php';

$db = new Database();

// Verifica se o usuário está logado
if (!isset($_SESSION['id'])) {
    header("Location: acesso_negado.php");
    exit();
}

// Adicione isso logo após a verificação do tipo de usuário
if ($_SESSION['tipo_usuario'] === 'gerente_banda') {
    // Captura as bandas do usuário logado
    $usuario_id = $_SESSION['id'];
    $stmt = $db->prepare("SELECT id, nome_banda FROM bandas WHERE usuario_id = ?");
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $bandas = $result->fetch_all(MYSQLI_ASSOC);

    // Captura os espaços disponíveis
    $stmt = $db->prepare("SELECT id, nome_espaco FROM espacos");
    $stmt->execute();
    $result = $stmt->get_result();
    $espacos = $result->fetch_all(MYSQLI_ASSOC);
}

if ($_SESSION['tipo_usuario'] === 'espaco_apresentacao') {
    // Captura as bandas disponíveis
    $stmt = $db->prepare("SELECT id, nome_banda FROM bandas");
    $stmt->execute();
    $result = $stmt->get_result();
    $bandas = $result->fetch_all(MYSQLI_ASSOC);

    // Captura os espaços do usuário logado
    $usuario_id = $_SESSION['id'];
    $stmt = $db->prepare("SELECT id, nome_espaco FROM espacos WHERE usuario_id = ?");
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $espacos = $result->fetch_all(MYSQLI_ASSOC);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_SESSION['tipo_usuario'] === 'gerente_banda') {
        // Insere a solicitação de agendamento
        $banda_id = $_POST['banda_id'];
        $espaco_id = $_POST['espaco_id'];
        $data_agendamento = $_POST['data_agendamento'];

        $stmt = $db->prepare("INSERT INTO solicitacoes_agendamento (banda_id, espaco_id, data_agendamento) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $banda_id, $espaco_id, $data_agendamento);

        if ($stmt->execute()) {
            echo "<script>alert('Solicitação de agendamento enviada com sucesso!');</script>";
        } else {
            echo "<script>alert('Erro ao enviar solicitação. Tente novamente.');</script>";
        }
    } elseif ($_SESSION['tipo_usuario'] === 'espaco_apresentacao') {
        // Solicitar show
        $banda_id = $_POST['banda_id'];
        $data_agendamento = $_POST['data_agendamento'];

        $stmt = $db->prepare("INSERT INTO solicitacoes_agendamento (banda_id, espaco_id, data_agendamento) VALUES (?, ?, ?)");
        $espaco_id = $_SESSION['id']; // ID do espaço de apresentação logado
        $stmt->bind_param("iis", $banda_id, $espaco_id, $data_agendamento);

        if ($stmt->execute()) {
            echo "<script>alert('Solicitação de show enviada com sucesso!');</script>";
        } else {
            echo "<script>alert('Erro ao enviar solicitação. Tente novamente.');</script>";
        }
    }
}
?>

<?php include 'php/includes/header.php'; ?>

<main>
    <section class="form-container">
        <h2><?php echo $_SESSION['tipo_usuario'] === 'gerente_banda' ? 'Agendar Show' : 'Solicitar Show'; ?></h2>
        <form method="post" action="">
            <?php if ($_SESSION['tipo_usuario'] === 'gerente_banda'): ?>
                <label for="banda_id">Selecione a Banda:</label>
                <select name="banda_id" id="banda_id" required>
                    <?php foreach ($bandas as $banda): ?>
                        <option value="<?php echo $banda['id']; ?>"><?php echo $banda['nome_banda']; ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="espaco_id">Selecione o Espaço:</label>
                <select name="espaco_id" id="espaco_id" required>
                    <?php foreach ($espacos as $espaco): ?>
                        <option value="<?php echo $espaco['id']; ?>"><?php echo $espaco['nome_espaco']; ?></option>
                    <?php endforeach; ?>
                </select>
            <?php else: ?>
                <label for="banda_id">Selecione a Banda:</label>
                <select name="banda_id" id="banda_id" required>
                    <?php foreach ($bandas as $banda): ?>
                        <option value="<?php echo $banda['id']; ?>"><?php echo $banda['nome_banda']; ?></option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>

            <label for="data_agendamento">Data do Agendamento:</label>
            <input type="datetime-local" name="data_agendamento" id="data_agendamento" required>

            <input type="submit" value="<?php echo $_SESSION['tipo_usuario'] === 'gerente_banda' ? 'Solicitar Agendamento' : 'Solicitar Show'; ?>">
        </form>
    </section>
</main>

<?php include 'php/includes/footer.php'; ?>