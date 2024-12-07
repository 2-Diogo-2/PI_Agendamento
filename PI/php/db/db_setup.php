<?php
// Carrega as variáveis de ambiente do arquivo .env
require_once __DIR__ . '/../dotenv.php';  // Caminho para dotenv.php

// Carrega as variáveis do .env usando o caminho absoluto
$dotenv = new DotEnv('C:/xampp/htdocs/PI/.env');  // Caminho absoluto para o arquivo .env
$dotenv->load();

// Configuração do banco de dados
$host = getenv('DB_HOST');
$user = getenv('DB_USER');
$password = getenv('DB_PASSWORD');
$dbname = getenv('DB_NAME');

// Conecta ao servidor MySQL
$conn = new mysqli($host, $user, $password);

// Verifica a conexão
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Cria o banco de dados, caso não exista
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Banco de dados criado ou já existente.";
} else {
    die("Erro ao criar o banco de dados: " . $conn->error);
}

// Seleciona o banco de dados
$conn->select_db($dbname);

// Cria as tabelas
$queries = [
    // Tabela de usuários
    "CREATE TABLE IF NOT EXISTS usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(255) NOT NULL,
        email VARCHAR(255) UNIQUE NOT NULL,
        senha VARCHAR(255) NOT NULL,
        tipo_usuario ENUM('gerente_banda', 'espaco_apresentacao', 'usuario_comum', 'administrador') NOT NULL,
        criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",

    // Tabela de imagens
    "CREATE TABLE IF NOT EXISTS imagens (
        id INT AUTO_INCREMENT PRIMARY KEY,
        caminho VARCHAR(255) NOT NULL,
        tipo ENUM('banda', 'espaco') NOT NULL,
        referencia_id INT NOT NULL
    )",

    // Tabela de avaliações
    "CREATE TABLE IF NOT EXISTS avaliacoes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        referencia_id INT NOT NULL,
        tipo ENUM('banda', 'espaco') NOT NULL,
        usuario_id INT NOT NULL,
        nota INT NOT NULL CHECK (nota BETWEEN 1 AND 5),
        comentario TEXT,
        data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
    )",

    // Tabela de agendamentos
    "CREATE TABLE IF NOT EXISTS agendamentos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        banda_id INT NOT NULL,
        espaco_id INT NOT NULL,
        data_agendamento DATETIME NOT NULL,
        status ENUM('pendente', 'confirmado', 'cancelado') DEFAULT 'pendente',
        FOREIGN KEY (banda_id) REFERENCES usuarios(id),
        FOREIGN KEY (espaco_id) REFERENCES usuarios(id)
    )"
];

foreach ($queries as $query) {
    if ($conn->query($query) !== TRUE) {
        die("Erro ao criar tabelas: " . $conn->error);
    }
}

echo "Tabelas criadas com sucesso.";
$conn->close();
?>
