<?php
require '../db/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $genero = $_POST['genero'];
    $descricao = $_POST['descricao'];
    $foto = $_FILES['foto']['name'];

    // Salvar imagem no diretório de uploads
    $uploadDir = '../../uploads/';
    $uploadFile = $uploadDir . basename($foto);

    if (move_uploaded_file($_FILES['foto']['tmp_name'], $uploadFile)) {
        $sql = "INSERT INTO bandas (nome, genero, descricao, foto) VALUES ('$nome', '$genero', '$descricao', '$foto')";

        if ($conn->query($sql) === TRUE) {
            echo "Banda cadastrada com sucesso!";
        } else {
            echo "Erro: " . $conn->error;
        }
    } else {
        echo "Erro ao enviar a foto.";
    }

    $conn->close();
}
?>
