<?php
// Incluir a classe de conexão com o banco de dados e a verificação de sessão
require_once 'php/includes/db_connect.php';

// Iniciar a sessão
session_start();

// Verificar se o usuário está logado e se o tipo de usuário é "Espaço de Apresentação"
if (!isset($_SESSION['id']) || $_SESSION['tipo'] != 'Espaço de Apresentação') {
    // Se não estiver logado ou o tipo não for "Espaço de Apresentação", redirecionar para login ou página de erro
    header("Location: login.php");
    exit();
}
?>

<?php include 'php/includes/header.php'; ?>

<main>
    <section class="form-container">
        <h2>Cadastro de Espaço de Apresentação</h2>
        <!-- Conteúdo do formulário de cadastro de espaço de apresentação -->
        <form method="post" action="">
            <!-- Campos de formulário para cadastro de espaço de apresentação -->
        </form>
    </section>
</main>

<?php include 'php/includes/footer.php'; ?>
