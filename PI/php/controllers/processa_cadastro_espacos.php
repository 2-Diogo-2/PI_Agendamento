<?php
session_start();
require_once '../includes/db_connect.php';

$db = new Database();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Captura os dados do formulário
    $nome_espaco = trim($_POST['nome_espaco']);
    $endereco = trim($_POST['endereco']);
    $cidade = trim($_POST['cidade']);
    $capacidade = $_POST['capacidade'];
    $tipo_ambiente = $_POST['tipo_ambiente'];
    $descricao = trim($_POST['descricao']);
    $email = trim($_POST['email']);  // Novo campo
    $redes_sociais = trim($_POST['redes_sociais']);  // Novo campo

    // Captura o ID do usuário logado
    $usuario_id = $_SESSION['id'];

    // Validação básica
    if (empty($nome_espaco) || empty($endereco) || empty($cidade) || empty($tipo_ambiente) || empty($descricao) || empty($email) || empty($redes_sociais) || !isset($capacidade) || $capacidade <= 0) {
        echo "<script>alert('Por favor, preencha todos os campos!'); history.back();</script>";
        exit;
    }

    // Verifica se o nome do espaço já está cadastrado
    $stmt = $db->prepare("SELECT id FROM espacos WHERE nome_espaco = ?");
    $stmt->bind_param("s", $nome_espaco);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<script>alert('Este nome de espaço já está cadastrado!'); history.back();</script>";
        $stmt->close();
        exit;
    }
    $stmt->close();

    // Inserir no banco de dados
    $stmt = $db->prepare("INSERT INTO espacos (nome_espaco, endereco, capacidade, tipo_ambiente, descricao, cidade, email, redes_sociais, usuario_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssisssssi", $nome_espaco, $endereco, $capacidade, $tipo_ambiente, $descricao, $cidade, $email, $redes_sociais, $usuario_id);

    if ($stmt->execute()) {
        $espaco_id = $stmt->insert_id; // ID do espaço recém-criado

        // Verifica se o diretório de uploads existe, se não, cria
        if (!is_dir('../uploads')) {
            mkdir('../uploads', 0755, true);
        }

        // Processar as fotos (caso haja)
        if (isset($_FILES['foto_espaco']) && count($_FILES['foto_espaco']['name']) > 0) {
            $foto_espaco = $_FILES['foto_espaco'];

            // Loop para fazer upload de todas as fotos
            foreach ($foto_espaco['name'] as $key => $value) {
                $foto_nome = $foto_espaco['name'][$key];
                $foto_tmp = $foto_espaco['tmp_name'][$key];
                $foto_error = $foto_espaco['error'][$key];
                $foto_size = $foto_espaco['size'][$key];

                // Verifica se não houve erro no upload
                if ($foto_error === 0 && $foto_size > 0) {
                    $foto_destino = '../uploads/' . uniqid() . '-' . basename($foto_nome);
                    if (move_uploaded_file($foto_tmp, $foto_destino)) {
                        // Inserir as fotos no banco
                        $stmt_foto = $db->prepare("INSERT INTO fotos_espaco (espaco_id, foto_url) VALUES (?, ?)");
                        $stmt_foto->bind_param("is", $espaco_id, $foto_destino);
                        if (!$stmt_foto->execute()) {
                            echo "<script>alert('Erro ao inserir foto no banco: " . $stmt_foto->error . "'); history.back();</script>";
                            exit;
                        }
                        $stmt_foto->close(); // Feche a declaração após a execução
                    } else {
                        echo "<script>alert('Erro ao mover o arquivo para o diretório de uploads.'); history.back();</script>";
                        exit;
                    }
                } else {
                    echo "<script>alert('Erro no upload da foto: " . $foto_espaco['error'][$key] . "'); history.back();</script>";
                    exit;
                }
        }
        }

        // Mensagem de sucesso
        echo "<script>alert('Cadastro de Espaço de Apresentação realizado com sucesso!'); history.back();</script>";
    } else {
        echo "<script>alert ('Erro ao realizar o cadastro do espaço. Tente novamente.'); history.back();</script>";
    }

    $stmt->close();
}
$db->close();
?>