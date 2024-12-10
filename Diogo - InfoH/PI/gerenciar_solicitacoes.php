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
$usuario_id = $_SESSION['id'];
$stmt = $db->prepare("SELECT sa.id, b.nome_banda, e.nome_espaco, sa.data_agendamento, sa.status, sa.usuario_id_destinatario, sa.usuario_id_solicitante 
                       FROM solicitacoes_agendamento sa 
                       JOIN bandas b ON sa.banda_id = b.id 
                       JOIN espacos e ON sa.espaco_id = e.id 
                       WHERE e.usuario_id = ? OR b.usuario_id = ?");
$stmt->bind_param("ii", $usuario_id, $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$solicitacoes = $result->fetch_all(MYSQLI_ASSOC);

// Processa as ações das solicitações
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $solicitacao_id = $_POST['solicitacao_id'];
    $acao = $_POST['acao'];

    // Verifica se os dados necessários estão presentes
    if (isset($solicitacao_id, $acao)) {
        // Verifica se o usuário é o destinatário ou o solicitante da solicitação
        $stmt = $db->prepare("SELECT usuario_id_destinatario, usuario_id_solicitante FROM solicitacoes_agendamento WHERE id = ?");
        $stmt->bind_param("i", $solicitacao_id);
        $stmt->execute();
        $stmt->bind_result($usuario_destinatario, $usuario_solicitante);
        $stmt->fetch();
        $stmt->close();

        if ($acao === 'aceitar' || $acao === 'negar' || $acao === 'negociar') {
            // Apenas o destinatário pode aceitar, negar ou negociar
            if ($usuario_id === $usuario_destinatario) {
                $status = ($acao === 'aceitar') ? 'aceito' : (($acao === 'negar') ? 'negado' : 'negociacao');
                $stmt = $db->prepare("UPDATE solicitacoes_agendamento SET status = ? WHERE id = ?");
                $stmt->bind_param("si", $status, $solicitacao_id);
                $stmt->execute();
                echo "<script>alert('Ação realizada com sucesso!');</script>";
            } else {
                echo "<script>alert('Você não tem permissão para realizar esta ação.');</script>";
            }
        } elseif ($acao === 'editar') {
            // Redireciona para a página de edição
            header("Location: editar_solicitacao.php?solicitacao_id=" . $solicitacao_id);
            exit();
        } elseif ($acao === 'excluir') {
            // Apenas o solicitante pode excluir
            if ($usuario_id === $usuario_solicitante) {
                $stmt = $db->prepare("DELETE FROM solicitacoes_agendamento WHERE id = ?");
                $stmt->bind_param("i", $solicitacao_id);
                $stmt->execute();
                echo "<script>alert('Solicitação excluída com sucesso!');</script>";
            } else {
                echo "<script>alert('Você não tem permissão para excluir esta solicitação.');</script>";
            }
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
                        <td><?php echo $solicitacao['id']; ?></td>
                        <td><?php echo $solicitacao['nome_banda']; ?></td>
                        <td><?php echo $solicitacao['nome_espaco']; ?></td>
                        <td><?php echo $solicitacao['data_agendamento']; ?></td>
                        <td><?php echo $solicitacao['status']; ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="solicitacao_id" value="<?php echo $solicitacao['id']; ?>">
                                <button type="submit" name="acao" value="aceitar">Aceitar</button>
                            </form>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="solicitacao_id" value="<?php echo $solicitacao['id']; ?>">
                                <button type="submit" name="acao" value="negar">Negar</button>
                            </form>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="solicitacao_id" value="<?php echo $solicitacao['id']; ?>">
                                <button type="submit" name="acao" value="negociar">Negociar</button>
                            </form>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="solicitacao_id" value="<?php echo $solicitacao['id']; ?>">
                                <button type="submit" name="acao" value="editar">Editar</button>
                            </form>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="solicitacao_id" value="<?php echo $solicitacao['id']; ?>">
                                <button type="submit" name="acao" value="excluir" onclick="return confirm('Tem certeza que deseja excluir esta solicitação?');">Excluir</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</main>

<?php include 'php/includes/footer.php'; ?>