<?php
// Iniciar a sessão
session_start();
require_once 'php/includes/db_connect.php';
define('BASE_URL', 'http://localhost/Diogo - InfoH/PI/');

$db = new Database();

// Verifica se o usuário está logado
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Função para buscar bandas com uma imagem principal
function getBandas($db) {
    $stmt = $db->prepare("
        SELECT b.id, b.nome_banda, b.genero, b.descricao,
               (SELECT fb.foto_url FROM fotos_banda fb WHERE fb.band_id = b.id LIMIT 1) AS foto_principal
        FROM bandas b
    ");
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Função para buscar espaços com uma imagem principal
function getEspacos($db) {
    $stmt = $db->prepare("
        SELECT e.id, e.nome_espaco, e.cidade, e.descricao,
               (SELECT fe.foto_url FROM fotos_espaco fe WHERE fe.espaco_id = e.id LIMIT 1) AS foto_principal
        FROM espacos e
    ");
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Recupera dados do banco
$bandas = getBandas($db);
$espacos = getEspacos($db);

$db->close();
?>

<?php include 'php/includes/header.php'; ?>

<main>
    <!-- Hero Section -->
    <section class="hero">
        <h1>Bem-vindo à Plataforma de Agendamento de Shows</h1>
        <p>Conectando bandas locais a espaços de apresentação em Minas Gerais.</p>
        <a href="cadastro.php" class="btn-primary">Cadastrar-se</a>
    </section>

    <!-- Lista de Bandas -->
    <section class="gallery">
    <h2>Bandas</h2>
    <div class="gallery-container">
        <?php foreach ($bandas as $banda): ?>
            <div class="gallery-item">
                <?php 
                // Corrigir o caminho da imagem da banda
                $imagem = !empty($banda['foto_principal']) 
                    ? BASE_URL . 'php/uploads/bandas/' . htmlspecialchars(basename($banda['foto_principal']))
                    : BASE_URL . 'php/uploads/default_banda.jpg';
                ?>
                <img src="<?= $imagem ?>" alt="<?= htmlspecialchars($banda['nome_banda']) ?>" class="gallery-img">
                <h3><?= htmlspecialchars($banda['nome_banda']) ?></h3>
                <p>Gênero: <?= htmlspecialchars($banda['genero']) ?></p>
                <a href="detalhes.php?id=<?= $banda['id'] ?>&tipo=banda" class="btn-secondary">Ver Detalhes</a>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Lista de Espaços -->
<section class="gallery">
    <h2>Espaços de Apresentação</h2>
    <div class="gallery-container">
        <?php foreach ($espacos as $espaco): ?>
            <div class="gallery-item">
                <?php 
                // Corrigir o caminho da imagem do espaço
                $imagem = !empty($espaco['foto_principal']) 
                    ? BASE_URL . 'php/uploads/espacos/' . htmlspecialchars(basename($espaco['foto_principal']))
                    : BASE_URL . 'php/uploads/default_espaco.jpg';
                ?>
                <img src="<?= $imagem ?>" alt="<?= htmlspecialchars($espaco['nome_espaco']) ?>" class="gallery-img">
                <h3><?= htmlspecialchars($espaco['nome_espaco']) ?></h3>
                <p>Cidade: <?= htmlspecialchars($espaco['cidade']) ?></p>
                <a href="detalhes.php?id=<?= $espaco['id'] ?>&tipo=espaco" class="btn-secondary">Ver Detalhes</a>
            </div>
        <?php endforeach; ?>
    </div>
</section>


</main>

<?php include 'php/includes/footer.php'; ?>
