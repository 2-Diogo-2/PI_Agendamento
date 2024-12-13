<?php
session_start();
require_once 'php/includes/db_connect.php';

$db = new Database();

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'nome';

$bandas = [];
$espacos = [];

// Filtragem de bandas
if ($filter === 'nome' || $filter === 'banda') {
    $stmt = $db->prepare("SELECT * FROM bandas WHERE LOWER(nome_banda) LIKE LOWER(?)");
    $search_param = "%" . $search . "%";
    $stmt->bind_param("s", $search_param);
    $stmt->execute();
    $result = $stmt->get_result();
    $bandas = $result->fetch_all(MYSQLI_ASSOC);
}

// Filtragem de espaços
if ($filter === 'localizacao' || $filter === 'espaco' || $filter === 'cidade') {
    $stmt = $db->prepare("SELECT * FROM espacos WHERE LOWER(nome_espaco) LIKE LOWER(?) OR LOWER(cidade) LIKE LOWER(?)");
    $search_param = "%" . $search . "%";
    $stmt->bind_param("ss", $search_param, $search_param);
    $stmt->execute();
    $result = $stmt->get_result();
    $espacos = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<?php include 'php/includes/header.php'; ?>

<main>
    <section class="form-container">
        <h2>Resultados da Pesquisa</h2>

        <h3>Bandas</h3>
        <ul>
            <?php if (count($bandas) > 0): ?>
                <?php foreach ($bandas as $banda): ?>
                    <li>
                        <a href="banda_detalhes.php?id=<?php echo $banda['id']; ?>">
                            <?php echo htmlspecialchars($banda['nome_banda']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>Nenhuma banda encontrada.</li>
            <?php endif; ?>
        </ul>

        <h3>Espaços</h3>
        <ul>
            <?php if (count($espacos) > 0): ?>
                <?php foreach ($espacos as $espaco): ?>
                    <li>
                        <a href="espaco_detalhes.php?id=<?php echo $espaco['id']; ?>">
                            <?php echo htmlspecialchars($espaco['nome_espaco']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>Nenhum espaço encontrado.</li>
            <?php endif; ?>
        </ul>
    </section>
</main>

<?php include 'php/includes/footer.php'; ?>