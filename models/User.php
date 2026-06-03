<?php
    class User {
        private $id;
        private $username;
        private $email;
        private $password;
        private $avatar_url;
        private $created_at;
        private $location;
        private $birth_date;
        private $gender;

        public function __construct($id = null, $username = "", $email = "", $password = "", $avatar_url = null, $created_at = null, $location = null, $birth_date = null, $gender = null) {
            $this->id = $id;
            $this->username = $username;
            $this->email = $email;
            $this->password = $password;
            $this->avatar_url = $avatar_url;
            $this->created_at = $created_at;
            $this->location = $location;
            $this->birth_date = $birth_date;
            $this->gender = $gender;
        }

        public function create($username, $email, $password, $avatar_url = null) {
            $this->username = $username;
            $this->email = $email;
            $this->password = password_hash($password, PASSWORD_BCRYPT);
            $this->avatar_url = $avatar_url;
            $this->created_at = date('Y-m-d H:i:s');
            return $this;
        }

        public function getId() { return $this->id; }
        public function setId($id) { $this->id = $id; }

        public function getUsername() { return $this->username; }
        public function getEmail() { return $this->email; }
        public function getPassword() { return $this->password; }
        
        public function getAvatarUrl() { return $this->avatar_url; }
        public function setAvatarUrl($avatar_url) { $this->avatar_url = $avatar_url; }
        
        public function getCreatedAt() { return $this->created_at; }
        
        public function getLocation() { return $this->location; }
        public function setLocation($location) { $this->location = $location; }
        
        public function getBirthDate() { return $this->birth_date; }
        public function setBirthDate($birth_date) { $this->birth_date = $birth_date; }
        
        public function getGender() { return $this->gender; }
        public function setGender($gender) { $this->gender = $gender; }
    }
?>