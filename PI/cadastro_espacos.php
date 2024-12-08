<?php
session_start();

// Verificar se o usuário tem acesso à página
if (!isset($_SESSION['id']) || 
    ($_SESSION['tipo_usuario'] !== 'espaco_apresentacao' && $_SESSION['tipo_usuario'] !== 'administrador')) {
    header("Location: acesso_negado.php");
    exit();
}
?>

<?php include 'php/includes/header.php'; ?>

<main>
    <section class="form-container">
        <h2>Cadastro de Espaço de Apresentação</h2>
        <form method="post" action="php/controllers/processa_cadastro_espacos.php" enctype="multipart/form-data">
    <label for="nome_espaco">Nome do Espaço:</label>
    <input type="text" name="nome_espaco" id="nome_espaco" required>

    <label for="endereco">Endereço:</label>
    <input type="text" name="endereco" id="endereco" required>

    <label for="cidade">Cidade:</label>
    <input type="text" name="cidade" id="cidade" required> <!-- Campo já existente -->

    <label for="capacidade">Capacidade:</label>
    <input type="number" name="capacidade" id="capacidade" required>

    <label for="tipo_ambiente">Tipo de Ambiente:</label>
    <select name="tipo_ambiente" id="tipo_ambiente" required>
        <option value="interno">Interno</option>
        <option value="externo">Externo</option>
        <option value="misturado">Misturado</option>
    </select>

    <label for="descricao">Descrição:</label>
    <textarea name="descricao" id="descricao" required></textarea>

    <!-- Novos campos -->
    <label for="email">Email para contato:</label>
    <input type="email" name="email" id="email" required>

    <label for="redes_sociais">Redes Sociais:</label>
    <textarea name="redes_sociais" id="redes_sociais" required></textarea>

    <label for="foto_espaco">Fotos do Espaço:</label>
    <input type="file" name="foto_espaco[]" id="foto_espaco" accept="image/*" multiple>

    <input type="submit" value="Cadastrar Espaço">
</form>


    </section>
</main>

<?php include 'php/includes/footer.php'; ?>
