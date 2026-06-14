<?php
    class Vote {
        private $user_id;
        private $post_id;
        private $vote_type;

        public function __construct($user_id = null, $post_id = null, $vote_type = null) {
            $this->user_id = $user_id;
            $this->post_id = $post_id;
            $this->vote_type = $vote_type;
        }

        public function create($user_id, $post_id, $vote_type) {
            $this->user_id = $user_id;
            $this->post_id = $post_id;
            $this->vote_type = $vote_type;
            return $this;
        }

        public function getUserId() { return $this->user_id; }

        public function getPostId() { return $this->post_id; }

        public function getVoteType() { return $this->vote_type; }
    }
?>