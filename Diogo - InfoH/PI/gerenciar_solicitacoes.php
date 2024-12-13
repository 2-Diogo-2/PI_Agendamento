<?php
session_start();
require_once 'php/includes/db_connect.php';

$db = new Database();

// Verifica se o usuário está logado
if (!isset($_SESSION['id'])) {
    header("Location: acesso_negado.php");
    exit();
}

// Captura as solicitações de agendamento
$stmt = $db->prepare("SELECT sa.id, b.nome_banda, e.nome_espaco, sa.data_agendamento, sa.status 
                       FROM solicitacoes_agendamento sa 
                       JOIN bandas b ON sa.banda_id = b.id 
                       JOIN espacos e ON sa.espaco_id = e.id");
$stmt->execute();
$result = $stmt->get_result();
$solicitacoes = $result->fetch_all(MYSQLI_ASSOC);

// Processa as ações das solicitações
// Processa as ações das solicitações
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $solicitacao_id = $_POST['solicitacao_id'] ?? null;
    $acao = $_POST['acao'] ?? null;

    if ($solicitacao_id && $acao) {
        if ($acao === 'aceitar') {
            $status = 'aceito';
        } elseif ($acao === 'negar') {
            $status = 'negado';
        } elseif ($acao === 'editar') {
            header("Location: editar_solicitacao.php?solicitacao_id=" . $solicitacao_id);
            exit();
        }

        if (isset($status)) {
            $stmt = $db->prepare("UPDATE solicitacoes_agendamento SET status = ? WHERE id = ?");
            $stmt->bind_param("si", $status, $solicitacao_id);
            $stmt->execute();
            // Redireciona para a mesma página para atualizar
            header("Location: gerenciar_solicitacoes.php");
            exit();
        }
    } else {
        echo "<script>alert('Dados necessários não foram enviados.');</script>";
    }
}
?>

<?php include 'php/includes/header.php'; ?>

<main>
    <section class="form-container">
        <h2>Gerenciar Solicitações de Agendamento</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Banda</th>
                    <th>Espaço</th>
                    <th>Data</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($solicitacoes as $solicitacao): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($solicitacao['id']); ?></td>
                        <td><?php echo htmlspecialchars($solicitacao['nome_banda']); ?></td>
                        <td><?php echo htmlspecialchars($solicitacao['nome_espaco']); ?></td>
                        <td><?php echo htmlspecialchars($solicitacao['data_agendamento']); ?></td>
                        <td><?php echo htmlspecialchars($solicitacao['status']); ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="solicitacao_id" value="<?php echo $solicitacao['id']; ?>">
                                <button type="submit" name="acao" value="aceitar">Aceitar</button>
                                <button type="submit" name="acao" value="negar">Negar</button>
                                <button type="submit" name="acao" value="editar">Editar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</main>

<?php include 'php/includes/footer.php'; ?>