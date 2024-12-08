<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Incluir a classe de conexão com o banco de dados
require_once 'php/includes/db_connect.php';

// Criar uma instância da classe Database
$db = new Database();

// Captura o ID do usuário logado
$usuario_id = $_SESSION['id'];

// Lógica para atualizar o perfil
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['delete'])) {
    // Captura os dados do formulário
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $tipo_usuario = $_POST['tipo_usuario'];

    // Validação básica
    if (empty($nome) || empty($email) || empty($tipo_usuario)) {
        echo "<script>alert('Por favor, preencha todos os campos!'); history.back();</script>";
        exit;
    }

    // Atualiza os dados do usuário no banco de dados
    $stmt = $db->prepare("UPDATE usuarios SET nome = ?, email = ?, tipo_usuario = ? WHERE id = ?");
    $stmt->bind_param("sssi", $nome, $email, $tipo_usuario, $usuario_id);

    if ($stmt->execute()) {
        echo "<script>alert('Perfil atualizado com sucesso!'); window.location.href = 'perfil.php';</script>";
    } else {
        echo "<script>alert('Erro ao atualizar perfil. Tente novamente.'); history.back();</script>";
    }

    $stmt->close();
}

// Lógica para excluir o perfil
if (isset($_POST['delete'])) {
    // Exclui os registros relacionados na tabela espacos
    $stmt = $db->prepare("DELETE FROM espacos WHERE usuario_id = ?");
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $stmt->close();

    // Exclui o usuário
    $stmt = $db->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $usuario_id);

    if ($stmt->execute()) {
        echo "<script>alert('Perfil excluído com sucesso!'); window.location.href = 'logout.php';</script>";
    } else {
        echo "<script>alert('Erro ao excluir perfil. Tente novamente.'); history.back();</script>";
    }

    $stmt->close();
}

// Busca os dados do usuário para exibição
$stmt = $db->prepare("SELECT nome, email, tipo_usuario FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$stmt->bind_result($nome, $email, $tipo_usuario);
$stmt->fetch();
$stmt->close();

$db->close();
?>

<?php include 'php/includes/header.php'; ?>

<main>
    <section class="form-container">
        <h2>Editar Perfil</h2>
        <form method="post" action="">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" value="<?php echo htmlspecialchars($nome); ?>" required>

            <label for="email">E-mail:</label>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>" required>

            <label for="tipo_usuario">Tipo de usuário:</label>
            <select name="tipo_usuario" id="tipo_usuario" required>
                <option value="usuario_comum" <?php echo ($tipo_usuario == 'usuario_comum') ? 'selected' : ''; ?>>Usuário Comum</option>
                <option value="gerente_banda" <? ```php
echo ($tipo_usuario == 'gerente_banda') ? 'selected' : ''; ?>>Gerente de Banda</option>
                <option value="espaco_apresentacao" <?php echo ($tipo_usuario == 'espaco_apresentacao') ? 'selected' : ''; ?>>Espaço de Apresentação</option>
                <option value="administrador" <?php echo ($tipo_usuario == 'administrador') ? 'selected' : ''; ?>>Administrador</option>
            </select>

            <input type="submit" value="Atualizar">
        </form>

        <form method="post" action="" onsubmit="return confirm('Tem certeza que deseja excluir seu perfil? Esta ação não pode ser desfeita.');">
            <input type="submit" name="delete" value="Excluir Perfil" style="background-color: red; color: white;">
        </form>
    </section>
</main>

<?php include 'php/includes/footer.php'; ?>