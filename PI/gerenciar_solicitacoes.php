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
$stmt = $db->prepare("SELECT sa.id, b.nome_banda, e.nome_espaco, sa.data_agendamento, sa.status, sa.banda_id, sa.espaco_id, 
                       sa.usuario_id_destinatario, sa.usuario_id_solicitante 
                       FROM solicitacoes_agendamento sa 
                       JOIN bandas b ON sa.banda_id = b.id 
                       JOIN espacos e ON sa.espaco_id = e.id 
                       WHERE e.usuario_id = ? OR b.usuario_id = ?");

if ($stmt === false) {
    die("Erro na preparação da consulta: " . $db->error);
}
$stmt->bind_param("ii", $usuario_id, $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$solicitacoes = $result->fetch_all(MYSQLI_ASSOC);

// Adicione o seguinte código para depuração
foreach ($solicitacoes as $solicitacao) {
    echo "Destinatário: " . $solicitacao['usuario_id_destinatario'] . " - Usuário Logado: " . $usuario_id . "<br>";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $solicitacao_id = $_POST['solicitacao_id'];
    $acao = $_POST['acao'];

    // Verifica se o usuário é o destinatário ou o solicitante da solicitação
    $stmt = $db->prepare("SELECT usuario_id_destinatario, usuario_id_solicitante FROM solicitacoes_agendamento WHERE id = ?");
    $stmt->bind_param("i", $solicitacao_id);
    $stmt->execute();
    $stmt->bind_result($usuario_destinatario, $usuario_solicitante);
    $stmt->fetch();
    $stmt->close();

    if ($acao === 'aceitar' || $acao === 'negar' || $acao === 'negociar') {
        // Apenas o destinatário pode aceitar, negar ou negociar
        if ($usuario_destinatario !== $usuario_id) {
            echo "<script>alert('Você não tem permissão para realizar esta ação.');</script>";
        } else {
            if ($acao === 'aceitar') {
                $stmt = $db->prepare("UPDATE solicitacoes_agendamento SET status = 'aceito' WHERE id = ?");
            } elseif ($acao === 'negar') {
                $stmt = $db->prepare("UPDATE solicitacoes_agendamento SET status = 'negado' WHERE id = ?");
            } elseif ($acao === 'negociar') {
                $stmt = $db->prepare("UPDATE solicitacoes_agendamento SET status = 'negociacao' WHERE id = ?");
            }
            $stmt->bind_param("i", $solicitacao_id);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                echo "<script>alert('Ação realizada com sucesso!');</script>";
            } else {
                echo "<script>alert('Erro ao atualizar a solicitação.');</script>";
            }
        }
    } elseif ($acao === 'excluir') {
        $stmt = $db->prepare("DELETE FROM solicitacoes_agendamento WHERE id = ?");
        $stmt->bind_param("i", $solicitacao_id);
        $stmt->execute();
        echo "<script>alert('Solicitação excluída com sucesso!');</script>";
        header("Refresh:0");
        exit();
    } elseif ($acao === 'editar') {
        // Verifica se a solicitação é do usuário solicitante e se o status é 'pendente'
        if ($usuario_solicitante === $usuario_id) {
            // Redireciona para a página de edição
            header("Location: editar_solicitacao.php?solicitacao_id=" . $solicitacao_id);
            exit();
        } else {
            echo "<script>alert('Você não pode editar esta solicitação.');</script>";
        }
    }
}
?>

<?php include 'php/includes/header.php'; ?>

<main>
    <section class="form-container">
        <h2>Gerenciar Solicitações de Agendamento</h2>
        <table>
            <tr>
                <th>Banda</th>
                <th>Espaço</th>
                <th>Data do Agendamento</th>
                <th>Status</th>
                <th >Ações</th>
            </tr>
            <?php foreach ($solicitacoes as $solicitacao): ?>
            <tr>
                <td><?php echo htmlspecialchars($solicitacao['nome_banda']); ?></td>
                <td><?php echo htmlspecialchars($solicitacao['nome_espaco']); ?></td>
                <td><?php echo date('d/m/Y H:i', strtotime($solicitacao['data_agendamento'])); ?></td>
                <td><?php echo ucfirst($solicitacao['status']); ?></td>
                <td>
                    <form method="post" action="">
                    <input type="hidden" name="solicitacao_id" value=" <?php echo htmlspecialchars($solicitacao['id']); ?>">       
                      <?php if ($solicitacao['usuario_id_destinatario'] == $usuario_id && $solicitacao['status'] === 'pendente'): ?>  
                            <button type="submit" name="acao" value="aceitar">Aceitar</button>
                            <button type="submit" name="acao" value="negar">Negar</button>
                            <button type="submit" name="acao" value="negociar">Negociar</button>
                        <?php endif; ?>
                        <button type="submit" name="acao" value="excluir">Excluir</button>
                        <?php if ($solicitacao['usuario_id_solicitante'] == $usuario_id): ?>
                            <button type="submit" name="acao" value="editar">Editar</button>
                        <?php endif; ?>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </section>
</main>

<?php include 'php/includes/footer.php'; ?>