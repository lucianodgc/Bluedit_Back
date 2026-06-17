<?php
    class Comment {
        private $id;
        private $user_id;
        private $post_id;
        private $content;
        private $creation_date;
        
        public function __construct($id = null, $user_id = null, $post_id = null, $content = "", $creation_date = null) {
            $this->id = $id;
            $this->user_id = $user_id;
            $this->post_id = $post_id;
            $this->content = $content;
            $this->creation_date = $creation_date;
        }

        public function create($user_id, $post_id, $content) {
            $this->user_id = $user_id;
            $this->post_id = $post_id;
            $this->content = $content;
            $this->creation_date = date('Y-m-d H:i:s');
            return $this;
        }

        public function getId() {
            return $this->id;
        }

        public function getUserId() {
            return $this->user_id;
        }

        public function getPostId() {
            return $this->post_id;
        }

        public function getContent() {
            return $this->content;
        }

        public function getCreationDate() {
            return $this->creation_date;
        }
    }