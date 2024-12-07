<?php
// Iniciar a sessão
session_start();

// Verifica se o usuário está logado e se é do tipo correto
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'Gerente da banda') {
    // Se não estiver logado ou o tipo de usuário for diferente, redireciona para a página de login
    header("Location: login.php");
    exit();
}
?>

<?php include 'php/includes/header.php'; ?>

<main>
  <section class="form-container">
    <h2>Cadastro de Banda</h2>
    <!-- Formulário de cadastro de banda -->
    <form method="post" action="processa_cadastro_banda.php">
        <label for="nome_banda">Nome da Banda:</label>
        <input type="text" name="nome_banda" id="nome_banda" required>
        
        <label for="genero">Gênero Musical:</label>
        <input type="text" name="genero" id="genero" required>

        <!-- Outros campos aqui -->

        <input type="submit" value="Cadastrar Banda">
    </form>
  </section>
</main>

<?php include 'php/includes/footer.php'; ?>
