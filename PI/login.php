<?php
// Iniciar a sessão
session_start();

// Se o usuário já estiver logado, redireciona para o index
if (isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

// Incluir a classe de conexão com o banco de dados
require_once 'php/includes/db_connect.php';

// Criar uma instância da classe Database
$db = new Database();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Captura os dados do formulário
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);
    $nome_usuario = trim($_POST['nome_usuario']); // Capturando o nome de usuário

    // Verifica se os campos foram preenchidos
    if (empty($email) || empty($senha) || empty($nome_usuario)) {
        $erro = "Por favor, preencha todos os campos.";
    } else {
        // Verifica as credenciais no banco de dados
        $stmt = $db->prepare("SELECT id, nome, senha, tipo_usuario FROM usuarios WHERE email = ? AND nome = ?");
        $stmt->bind_param("ss", $email, $nome_usuario); // Bind para e-mail e nome de usuário
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $nome, $senhaHash, $tipo);
            $stmt->fetch();

            // Verifica a senha
            if (password_verify($senha, $senhaHash)) {
                // Salva as informações do usuário na sessão
                $_SESSION['id'] = $id;
                $_SESSION['nome'] = $nome; // Armazenando o nome do usuário
                $_SESSION['tipo'] = $tipo;

                // Redireciona para o index.php após login bem-sucedido
                header("Location: index.php");
                exit();
            } else {
                $erro = "Credenciais inválidas.";
            }
        } else {
            $erro = "Usuário não encontrado.";
        }
        $stmt->close();
    }
    $db->close();
}
?>

<?php include 'php/includes/header.php'; ?>

<main>
    <section class="form-container">
        <h2>Login</h2>
        <?php if (!empty($erro)) : ?>
            <p class="error"><?php echo $erro; ?></p>
        <?php endif; ?>
        <form method="post" action="">
            <label for="nome_usuario">Nome de Usuário:</label>
            <input type="text" name="nome_usuario" id="nome_usuario" required>

            <label for="email">E-mail:</label>
            <input type="email" name="email" id="email" required>

            <label for="senha">Senha:</label>
            <input type="password" name="senha" id="senha" required>

            <input type="submit" value="Entrar">
        </form>
        <p class="link">Ainda não tem conta? <a href="cadastro.php">Cadastre-se</a></p>
    </section>
</main>

<?php include 'php/includes/footer.php'; ?>
