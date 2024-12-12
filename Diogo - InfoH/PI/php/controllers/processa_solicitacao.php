<?php
session_start();
require_once '../includes/db_connect.php';

$db = new Database();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $solicitacao_id = $_POST['solicitacao_id'] ?? null;
    $acao = $_POST['acao'] ?? null;

    if ($solicitacao_id && $acao) {
        // Verifica se o usuário é o destinatário ou o solicitante da solicitação
        $stmt = $db->prepare("SELECT usuario_id_destinatario, usuario_id_solicitante FROM solicitacoes_agendamento WHERE id = ?");
        $stmt->bind_param("i", $solicitacao_id);
        $stmt->execute();
        $stmt->bind_result($usuario_destinatario, $usuario_solicitante);
        $stmt->fetch();
        $stmt->close();

        if ($acao === 'aceitar' || $acao === 'negar' || $acao === 'negociar') {
            if ($_SESSION['id'] == $usuario_destinatario) {
                $status = ($acao === 'aceitar') ? 'aceito' : (($acao === 'negar') ? 'negado' : 'negociacao');
                $stmt = $db->prepare("UPDATE solicitacoes_agendamento SET status = ? WHERE id = ?");
                $stmt->bind_param("si", $status, $solicitacao_id);
                $stmt->execute();
                echo "Ação realizada com sucesso!";
            } else {
                echo "Você não tem permissão para realizar esta ação.";
            }
        } elseif ($acao === 'excluir') {
            if ($_SESSION['id'] == $usuario_solicitante) {
                $stmt = $db->prepare("DELETE FROM solicitacoes_agendamento WHERE id = ?");
                $stmt->bind_param("i", $solicitacao_id);
                $stmt->execute();
                echo "Solicitação excluída com sucesso!";
            } else {
                echo "Você não tem permissão para excluir esta solicitação.";
            }
        }
    } else {
        echo "Dados inválidos.";
    }
} else {
    echo "Método de requisição inválido.";
}
?>