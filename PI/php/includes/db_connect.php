<?php
class Database {
    private $host = 'localhost';
    private $user = 'root';
    private $password = '';
    private $dbname = 'plataforma_shows';
    private $conn;

    // Construtor para estabelecer a conexão
    public function __construct() {
        $this->conn = new mysqli($this->host, $this->user, $this->password, $this->dbname);

        // Verificar se a conexão foi bem-sucedida
        if ($this->conn->connect_error) {
            die("Falha na conexão com o banco de dados: " . $this->conn->connect_error);
        }
    }

    // Método para preparar e retornar a consulta
    public function prepare($sql) {
        return $this->conn->prepare($sql);
    }

    // Método para fechar a conexão
    public function close() {
        $this->conn->close();
    }

    // Método para verificar se a conexão está ativa
    public function ping() {
        return $this->conn->ping();
    }
}
?>
