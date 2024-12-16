<?php
session_start();
require_once 'php/includes/db_connect.php';

$db = new Database();

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'nome';

$bandas = [];

// Filtragem de bandas
if (!empty($search)) {
    if ($filter === 'nome' || $filter === 'banda') {
        $stmt = $db->prepare("SELECT * FROM bandas WHERE LOWER(nome_banda) LIKE LOWER(?)");
        $search_param = "%" . $search . "%";
        $stmt->bind_param("s", $search_param);
        $stmt->execute();
        $result = $stmt->get_result();
        $bandas = $result->fetch_all(MYSQLI_ASSOC);
    } elseif ($filter === 'espaco') {
        $stmt = $db->prepare("SELECT * FROM bandas WHERE LOWER(espaco) LIKE LOWER(?)");
        $search_param = "%" . $search . "%";
        $stmt->bind_param("s", $search_param);
        $stmt->execute();
        $result = $stmt->get_result();
        $bandas = $result->fetch_all(MYSQLI_ASSOC);
    } elseif ($filter === 'cidade') {
        $stmt = $db->prepare("SELECT * FROM bandas WHERE LOWER(cidade) LIKE LOWER(?)");
        $search_param = "%" . $search . "%";
        $stmt->bind_param("s", $search_param);
        $stmt->execute();
        $result = $stmt->get_result();
        $bandas = $result->fetch_all(MYSQLI_ASSOC);
    }
} else {
    // Se não houver pesquisa, retornar todas as bandas
    $stmt = $db->prepare("SELECT * FROM bandas");
    $stmt->execute();
    $result = $stmt->get_result();
    $bandas = $result->fetch_all(MYSQLI_ASSOC);
}

?>

<?php include 'php/includes/header.php'; ?>

<main>
    <section class="form-container">
        <h2>Todas as Bandas</h2>
        <form method="GET" action="">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Pesquisar...">
            <select name="filter">
                <option value="nome" <?php echo ($filter === 'nome') ? 'selected' : ''; ?>>Por Nome</option>
                <option value="espaco" <?php echo ($filter === 'espaco') ? 'selected' : ''; ?>>Por Espaço</option>
                <option value="banda" <?php echo ($filter === 'banda') ? 'selected' : ''; ?>>Por Banda</option>
                <option value="cidade" <?php echo ($filter === 'cidade') ? 'selected' : ''; ?>>Por Cidade</option>
            </select>
            <button type="submit">Pesquisar</button>
        </form>
        <table>
            <thead>
                <tr>
                    <th>Nome da Banda</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bandas as $banda): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($banda['nome_banda']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</main>

<?php include 'php/includes/footer.php'; ?>
