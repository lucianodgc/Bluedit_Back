<?php
    include_once __DIR__ . "/Config.php";
    class Connect{
        private $host = SERVER;
        private $user = USER;
        private $pass = PASSWORD;
        private $db = DATABASE;
        private $connection;
        
        public function getConnection(){
            try {
                $this->connection = new mysqli($this->host, $this->user, $this->pass, $this->db);
            } catch (mysqli_sql_exception $e) {
                echo "Error: " . $e->getMessage();
                return false;
            }
            return $this->connection;
        }
    }
?>