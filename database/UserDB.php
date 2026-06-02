<?php
    include_once __DIR__ . "/../config/Connect.php";
    include_once __DIR__ . "/../models/User.php";

    class UserDB {
        private $mysql;

        public function __construct() {
            $connect = new Connect();
            $this->mysql = $connect->getConnection();
        }

        public function register($user) {
            if ($this->getByEmail($user->getEmail())) {
                return ["error" => "El email ya está registrado"];
            }
            if ($this->getByUsername($user->getUsername())) {
                return ["error" => "El nombre de usuario ya está ocupado"];
            }

            $sql = "INSERT INTO users (username, email, password, avatar_url, created_at) VALUES (?, ?, ?, ?, ?)";
            $query = $this->mysql->prepare($sql);
            
            $username = $user->getUsername();
            $email = $user->getEmail();
            $password = $user->getPassword();
            $avatar = $user->getAvatarUrl();
            $date = $user->getCreatedAt();

            $query->bind_param("sssss", $username, $email, $password, $avatar, $date);
            $query->execute();

            if ($query->affected_rows > 0) {
                return ["id" => $query->insert_id];
            }
            return ["error" => "No se pudo crear el usuario"];
        }

        public function getByEmail($email) {
            $sql = "SELECT id, username, email, password, avatar_url AS avatarUrl FROM users WHERE email = ?";
            $query = $this->mysql->prepare($sql);
            $query->bind_param("s", $email);
            $query->execute();
            
            $result = $query->get_result();
            if ($row = $result->fetch_assoc()) {
                return $row;
            }
            return null;
        }

        public function getByUsername($username) {
            $sql = "SELECT id FROM users WHERE username = ?";
            $query = $this->mysql->prepare($sql);
            $query->bind_param("s", $username);
            $query->execute();

            $result = $query->get_result();
            if ($row = $result->fetch_assoc()) {
                return $row;
            }
            return null;
        }

        public function getById($id) {
            $sql = "SELECT id, username, email, avatar_url AS avatarUrl, created_at AS createdAt FROM users WHERE id = ?";
            $query = $this->mysql->prepare($sql);
            $query->bind_param("i", $id);
            $query->execute();
            
            $result = $query->get_result();
            if ($row = $result->fetch_assoc()) {
                return $row;
            }
            return null;
        }

        public function updateProfile($id, $username, $avatarUrl) {
            $existingUser = $this->getByUsername($username);
            if ($existingUser && $existingUser['id'] != $id) {
                return false;
            }

            $sql = "UPDATE users SET username = ?, avatar_url = ? WHERE id = ?";
            $query = $this->mysql->prepare($sql);
            $query->bind_param("ssi", $username, $avatarUrl, $id);
            
            if ($query->execute()) {
                return true; 
            }
            return false;
        }

        public function deleteUser($id) {
            $sql = "DELETE FROM users WHERE id = ?";
            $query = $this->mysql->prepare($sql);
            $query->bind_param("i", $id);
            $query->execute();
            
            if ($query->affected_rows > 0) {
                return true;
            }
            return false;
        }
    }
?>