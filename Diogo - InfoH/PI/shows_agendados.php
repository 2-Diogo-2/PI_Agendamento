<?php
session_start();
require_once 'php/includes/db_connect.php';

$db = new Database();

// Verifica se o usuário está logado
if (!isset($_SESSION['id'])) {
    header("Location: acesso_negado.php");
    exit();
}

// Captura todas as solicitações de agendamento aceitas
$stmt = $db->prepare("SELECT * FROM solicitacoes_agendamento WHERE status = 'aceito'");
$stmt->execute();
$result = $stmt->get_result();
$solicitacoes = $result->fetch_all(MYSQLI_ASSOC);

// Captura as informações das bandas e espaços
$bandas = array();
$espacos = array();
foreach ($solicitacoes as $solicitacao) {
    $banda_id = $solicitacao['banda_id'];
    $espaco_id = $solicitacao['espaco_id'];

    // Captura a informação da banda
    $stmt = $db->prepare("SELECT nome_banda FROM bandas WHERE id = ?");
    $stmt->bind_param("i", $banda_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $banda = $result->fetch_assoc();
    $bandas[$banda_id] = $banda['nome_banda'];

    // Captura a informação do espaço
    $stmt = $db->prepare("SELECT nome_espaco FROM espacos WHERE id = ?");
    $stmt->bind_param("i", $espaco_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $espaco = $result->fetch_assoc();
    $espacos[$espaco_id] = $espaco['nome_espaco'];
}
?>

<?php include 'php/includes/header.php'; ?>

<main>
    <section class="shows-agendados">
        <h2>Shows Agendados</h2>
        <table>
            <thead>
                <tr>
                    <th>Data do Show</th>
                    <th>Banda</th>
                    <th>Espaço</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($solicitacoes as $solicitacao): ?>
                    <tr>
                        <td><?php echo date('d/m/Y H:i', strtotime($solicitacao['data_agendamento'])); ?></td>
                        <td><?php echo $bandas[$solicitacao['banda_id']]; ?></td>
                        <td><?php echo $espacos[$solicitacao['espaco_id']]; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</main>

<?php include 'php/includes/footer.php'; ?>