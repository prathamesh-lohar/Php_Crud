<?php

class Database {
    private $host = 'localhost';
    private $db_name = 'crud_app';
    private $db_user = 'root';
    private $db_password = '';
    private $charset = 'utf8mb4';
    private $socket = '/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock';
    
    private $pdo;
    
    public function connect() {
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->db_name . ';charset=' . $this->charset . ';unix_socket=' . $this->socket;
        
        $options = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        );
        
        try {
            $this->pdo = new PDO($dsn, $this->db_user, $this->db_password, $options);
            return $this->pdo;
        } catch(PDOException $e) {
            die('Connection Error: ' . $e->getMessage());
        }
    }
    
    public function getConnection() {
        if (!$this->pdo) {
            $this->connect();
        }
        return $this->pdo;
    }
}
?>
