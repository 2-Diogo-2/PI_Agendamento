<?php
// Iniciar a sessão
session_start();
require_once 'php/includes/db_connect.php';

$db = new Database();

// Verifica se o usuário está logado
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Função para buscar todas as bandas com suas fotos
function getBandas($db) {
  $stmt = $db->prepare("
      SELECT b.id, b.nome_banda, b.genero, b.descricao, b.email, b.redes_sociais, fb.foto_url AS foto_url
      FROM bandas b
      LEFT JOIN fotos_banda fb ON b.id = fb.band_id
  ");
  $stmt->execute();
  $result = $stmt->get_result();
  return $result->fetch_all(MYSQLI_ASSOC);
}

// Função para buscar todos os espaços de apresentação com suas fotos
function getEspacos($db) {
  $stmt = $db->prepare("
      SELECT e.id, e.nome_espaco, e.cidade, e.descricao, e.email, e.redes_sociais, fe.foto_url AS foto_url
      FROM espacos e
      LEFT JOIN fotos_espaco fe ON e.id = fe.espaco_id
  ");
  $stmt->execute();
  $result = $stmt->get_result();
  return $result->fetch_all(MYSQLI_ASSOC);
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
    <div class="hero-content">
      <h1>Bem-vindo à Plataforma de Agendamento de Shows</h1>
      <p>Conectando bandas locais a espaços de apresentação em Minas Gerais.</p>
      <a href="cadastro.php" class="btn-primary">Cadastrar-se</a>
    </div>
  </section>

<!-- Carrossel de Bandas -->
<!-- Carrossel de Bandas -->
<section class="carousel-section">
    <h2>Bandas</h2>
    <div class="carousel">
      <?php foreach ($bandas as $banda): ?>
        <div class="carousel-item">
          <img src="./uploads/<?= htmlspecialchars($banda['foto_url']) ?>" alt="<?= htmlspecialchars($banda['nome_banda']) ?>"> <!-- Use foto_url -->
          <h3><?= htmlspecialchars($banda['nome_banda']) ?></h3>
          <p><?= htmlspecialchars($banda['genero']) ?></p>
          <a href="banda_detalhes.php?id=<?= $banda['id'] ?>" class="btn-secondary">Ver Detalhes</a>
        </div>
      <?php endforeach; ?>
    </div>
</section>

<!-- Carrossel de Espaços de Apresentação -->
<section class="carousel-section">
    <h2>Espaços de Apresentação</h2>
    <div class="carousel">
      <?php foreach ($espacos as $espaco): ?>
        <div class="carousel-item">
          <img src="./uploads/<?= htmlspecialchars($espaco['foto_url']) ?>" alt="<?= htmlspecialchars($espaco['nome_espaco']) ?>"> <!-- Use foto_url -->
          <h3><?= htmlspecialchars($espaco['nome_espaco']) ?></h3>
          <p><?= htmlspecialchars($espaco['cidade']) ?></p>
          <a href="espaco_detalhes.php?id=<?= $espaco['id'] ?>" class="btn-secondary">Ver Detalhes</a>
        </div>
      <?php endforeach; ?>
    </div>
</section>

<?php include 'php/includes/footer.php'; ?>
