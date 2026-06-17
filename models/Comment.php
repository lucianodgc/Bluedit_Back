<?php
    class Comment {
        private $id;
        private $user_id;
        private $post_id;
        private $content;
        private $created_at;
        
        public function __construct($id = null, $user_id = null, $post_id = null, $content = "", $created_at = null) {
            $this->id = $id;
            $this->user_id = $user_id;
            $this->post_id = $post_id;
            $this->content = $content;
            $this->created_at = $created_at;
        }

        public function create($user_id, $post_id, $content) {
            $this->user_id = $user_id;
            $this->post_id = $post_id;
            $this->content = $content;
            $this->created_at = date('Y-m-d H:i:s');
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

        public function getCreatedAt() {
            return $this->created_at;
        }
    }