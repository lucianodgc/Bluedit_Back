<?php
    class Connect{
    private $connection;
    
    public function getConnection() {
        $host = $_ENV['DB_HOST'];
        $user = $_ENV['DB_USER'];
        $pass = $_ENV['DB_PASS'];
        $db   = $_ENV['DB_NAME'];
            try {
                $this->connection = new mysqli($host, $user, $pass, $db);
            } catch (mysqli_sql_exception $e) {
                echo "Error: " . $e->getMessage();
                return false;
            }
            return $this->connection;
        }
    }
?>