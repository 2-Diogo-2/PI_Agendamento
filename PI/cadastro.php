<?php
// Incluir a classe de conexão com o banco de dados
require_once 'php/includes/db_connect.php';

// Criar uma instância da classe Database
$db = new Database();

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Captura os dados do formulário
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = password_hash(trim($_POST['senha']), PASSWORD_DEFAULT);
    $tipo_usuario = $_POST['tipo_usuario'];

    // Validação básica
    if (empty($nome) || empty($email) || empty($senha) || empty($tipo_usuario)) {
        echo "<script>alert('Por favor, preencha todos os campos!'); history.back();</script>";
        exit;
    }

    // Verifica se o e-mail já está cadastrado
    $stmt = $db->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<script>alert('Este e-mail já está cadastrado!'); history.back();</script>";
    } else {
        // Inserir no banco de dados
        $sql = "INSERT INTO usuarios (nome, email, senha, tipo_usuario) VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ssss", $nome, $email, $senha, $tipo_usuario);

        if ($stmt->execute()) {
            echo "<script>alert('Cadastro realizado com sucesso!'); window.location.href = 'index.php';</script>";
        } else {
            echo "<script>alert('Erro ao realizar cadastro. Tente novamente.'); history.back();</script>";
        }
    }

    // Fechar o stmt (não feche a conexão aqui)
    $stmt->close();
}

// Fechar a conexão com o banco de dados após tudo ser executado
$db->close();
?>

<?php include 'php/includes/header.php'; ?>

<main>
    <section class="form-container">
        <h2>Cadastro</h2>
        <form method="post" action="">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" required>

            <label for="email">E-mail:</label>
            <input type="email" name="email" id="email" required>

            <label for="senha">Senha:</label>
            <input type="password" name="senha" id="senha" required>

            <label for="tipo_usuario">Tipo de usuário:</label>
            <select name="tipo_usuario" id="tipo_usuario" required>
                <option value="usuario_comum">Usuário Comum</option>
                <option value="gerente_banda">Gerente de Banda</option>
                <option value="espaco_apresentacao">Espaço de Apresentação</option>
                <option value="administrador">Administrador</option>
            </select>

            <input type="submit" value="Cadastrar">
        </form>
        <p class="link">Já tem conta? <a href="login.php">Faça login</a></p>
    </section>
</main>

<?php include 'php/includes/footer.php'; ?>
