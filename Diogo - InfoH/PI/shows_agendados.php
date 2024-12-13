<?php
session_start();
require_once 'php/includes/db_connect.php';

$db = new Database();

// Verifica se o usuário está logado
if (!isset($_SESSION['id'])) {
    header("Location: acesso_negado.php");
    exit();
}

// Captura o termo de pesquisa e o filtro, se houver
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'banda';

// Captura todas as solicitações de agendamento aceitas
$stmt = $db->prepare("SELECT * FROM solicitacoes_agendamento WHERE status = 'aceito'");

// Se houver um termo de pesquisa, modifica a consulta
if ($search) {
    if ($filter === 'banda') {
        $stmt = $db->prepare("SELECT * FROM solicitacoes_agendamento sa 
                               JOIN bandas b ON sa.banda_id = b.id 
                               WHERE sa.status = 'aceito' AND b.nome_banda LIKE ?");
    } elseif ($filter === 'espaco') {
        $stmt = $db->prepare("SELECT * FROM solicitacoes_agendamento sa 
                               JOIN espacos e ON sa.espaco_id = e.id 
                               WHERE sa.status = 'aceito' AND e.nome_espaco LIKE ?");
    } elseif ($filter === 'cidade') {
        $stmt = $db->prepare("SELECT * FROM solicitacoes_agendamento sa 
                               JOIN espacos e ON sa.espaco_id = e.id 
                               WHERE sa.status = 'aceito' AND e.cidade LIKE ?");
    }
    $search_param = "%" . $search . "%";
    $stmt->bind_param("s", $search_param);
}

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
    <section class="form-container">
        <h2>Shows Agendados</h2>
        
        <!-- Formulário de pesquisa -->
        <form method="GET" action="">
            <input type="text" name="search" placeholder="Pesquisar..." value="<?php echo htmlspecialchars($search); ?>">
            <select name="filter">
                <option value="banda" <?php echo ($filter === 'banda') ? 'selected' : ''; ?>>Por Banda</option>
                <option value="espaco" <?php echo ($filter === 'espaco') ? 'selected' : ''; ?>>Por Espaço</option>
                <option value="cidade" <?php echo ($filter === 'cidade') ? 'selected' : ''; ?>>Por Cidade</option>
            </select>
            <input type="submit" value="Pesquisar">
        </form>

        <table>
            <thead>
                <tr>
                    <th>Data do Show</th>
                    <th>Banda</th>
                    <th>Espaço</ ```php
                    <th>Espaço</th>
                    <th>Cidade</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($solicitacoes as $solicitacao): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($solicitacao['data_agendamento']); ?></td>
                        <td><?php echo htmlspecialchars($bandas[$solicitacao['banda_id']]); ?></td>
                        <td><?php echo htmlspecialchars($espacos[$solicitacao['espaco_id']]); ?></td>
                        <td><?php echo htmlspecialchars($espacos[$solicitacao['espaco_id']]['cidade']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</main>

<?php include 'php/includes/footer.php'; ?>