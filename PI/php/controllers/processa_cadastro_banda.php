<?php
session_start();
require_once '../includes/db_connect.php';

$db = new Database();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Captura os dados do formulário
    $nome_banda = trim($_POST['nome_banda']);
    $genero = trim($_POST['genero']);
    $descricao = trim($_POST['descricao']);
    $email = trim($_POST['email']);  // Novo campo
    $redes_sociais = trim($_POST['redes_sociais']);  // Novo campo

    // Captura o ID do usuário logado
    $usuario_id = $_SESSION['id'];

    // Validação básica
    if (empty($nome_banda) || empty($genero) || empty($descricao) || empty($email) || empty($redes_sociais)) {
        echo "<script>alert('Por favor, preencha todos os campos!'); history.back();</script>";
        exit;
    }

    // Após a validação dos dados do formulário
        $stmt = $db->prepare("INSERT INTO bandas (nome_banda, genero, descricao, email, redes_sociais, usuario_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssi", $nome_banda, $genero, $descricao, $email, $redes_sociais, $usuario_id);     

    if ($stmt->execute()) {
        $band_id = $stmt->insert_id;  // ID da banda recém-criada

        // Verifica se o diretório de uploads existe, se não, cria
        if (!is_dir('../uploads')) {
            mkdir('../uploads', 0755, true);
        }

        // Processar as fotos
        if (isset($_FILES['foto_banda']) && count($_FILES['foto_banda']['name']) > 0) {
            $foto_banda = $_FILES['foto_banda'];

            // Loop para fazer upload de todas as fotos
            foreach ($foto_banda['name'] as $key => $value) {
                $foto_nome = $foto_banda['name'][$key];
                $foto_tmp = $foto_banda['tmp_name'][$key];
                $foto_error = $foto_banda['error'][$key];
                $foto_size = $foto_banda['size'][$key];

                // Verifica se não houve erro no upload
                if ($foto_error === 0 && $foto_size > 0) {
                    $foto_destino = '../uploads/' . uniqid() . '-' . basename($foto_nome);
                    if (move_uploaded_file($foto_tmp, $foto_destino)) {
                        // Inserir as fotos no banco
                        $stmt_foto = $db->prepare("INSERT INTO fotos_banda (band_id, foto_url) VALUES (?, ?)");
                        $stmt_foto->bind_param("is", $band_id, $foto_destino);
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
                    echo "<script>alert('Erro no upload da foto: " . $foto_banda['error'][$key] . "'); history.back();</script>";
                    exit;
                }
            }
        }

        // Usando caminho absoluto para o redirecionamento
        echo "<script>alert('Cadastro de banda realizado com sucesso!'); history.back();</script>";
    } else {
        echo "<script>alert('Erro ao realizar cadastro. Tente novamente.'); history.back();</script>";
    }

    $stmt->close();
}
$db->close();
?>