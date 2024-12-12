<?php
session_start();

// Verificar se o usuário tem acesso à página
if (!isset($_SESSION['id']) || 
    ($_SESSION['tipo_usuario'] !== 'gerente_banda' && $_SESSION['tipo_usuario'] !== 'administrador')) {
    header("Location: acesso_negado.php");
    exit();
}
?>

<?php include 'php/includes/header.php'; ?>

<main>
  <section class="form-container">
    <h2>Cadastro de Banda</h2>
    <form method="post" action="php/controllers/processa_cadastro_banda.php" enctype="multipart/form-data"> <!-- Adicionado enctype -->
      <label for="nome_banda">Nome da Banda:</label>
      <input type="text" name="nome_banda" id="nome_banda" required>
      
      <label for="genero">Gênero Musical:</label>
      <input type="text" name="genero" id="genero" required>

      <label for="descricao">Descrição da Banda:</label>
      <textarea name="descricao" id="descricao" rows="4" required></textarea>

      <label for="email">Email para contato:</label>
      <input type="email" name="email" id="email" required>  <!-- Novo campo -->

      <label for="redes_sociais">Redes Sociais (links separados por vírgula):</label> <!-- Adicione uma descrição clara -->
      <textarea name="redes_sociais" id="redes_sociais" rows="2" required></textarea>  <!-- Novo campo -->

      <label for="foto_banda">Fotos da Banda:</label>
      <input type="file" name="foto_banda[]" id="foto_banda" accept="image/*" multiple required>

      <input type="submit" value="Cadastrar Banda">
    </form>
  </section>
</main>

<?php include 'php/includes/footer.php'; ?>