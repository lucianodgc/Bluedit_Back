<?php
    class Post {
        private $id;
        private $title;
        private $user_id;
        private $username;
        private $content;
        private $type;
        private $votes_count;
        private $comments_count;
        private $creation_date;

        public function __construct($id = null, $title = "", $content = "", $user_id = null, $creation_date = null) {
            $this->id = $id;
            $this->title = $title;
            $this->content = $content;
            $this->user_id = $user_id;
            $this->creation_date = $creation_date;
        }

        public function create($title, $user_id, $content, $type) {
            $this->title = $title;
            $this->user_id = $user_id;
            $this->content = $content;
            $this->type = $type; 
            $this->creation_date = date('Y-m-d H:i:s');
            return $this;
        }

        public function setId($id) {
            $this->id = $id;
        }

        public function setTitle($title) {
            $this->title = $title;
        }

        public function getId() {
            return $this->id;
        }
        
        public function getUserId() {
            return $this->user_id;
        }

        public function getContent() {
            return $this->content;
        }

        public function getCreationDate() {
            return $this->creation_date;
        }

        public function getTitle() {
            return $this->title;
        }

        public function getUsername() {
            return $this->username;
        }

        public function getVotesCount() {
            return $this->votes_count;
        }

        public function getCommentsCount() {
            return $this->comments_count;
        }

        public function getType() {
            return $this->type;
        }

    }
?>