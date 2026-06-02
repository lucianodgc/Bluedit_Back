<?php
    include_once __DIR__ . "/../config/Connect.php";
    include_once __DIR__ . "/../models/Post.php";

    class PostDB {
        private $mysql;

        public function __construct() {
            $connect = new Connect();
            $this->mysql = $connect->getConnection();
        }

        public function create($post) {

            $sql = "INSERT INTO posts (title, content, user_id, type, creation_date) VALUES (?, ?, ?, ?, ?)";
            
            $query = $this->mysql->prepare($sql);
            
            $title = $post->getTitle();
            $content = $post->getContent();
            $userId = $post->getUserId();
            $type = $post->getType();
            $date = $post->getCreationDate();

            $query->bind_param("ssiss", $title, $content, $userId, $type, $date);
            $query->execute();

            if ($query->affected_rows > 0) {
                return $query->insert_id;
            }
            return false;
        }

        public function getPosts() {
            $sql = "SELECT p.id, p.title, p.content, p.type,
                        p.user_id AS userId, 
                        p.votes_count AS votesCount, 
                        p.comments_count AS commentsCount, 
                        p.creation_date AS createdAt, 
                        u.username,
                        u.avatar_url AS avatarUrl
                    FROM posts p 
                    INNER JOIN users u ON p.user_id = u.id 
                    ORDER BY p.creation_date DESC";
                    
            $result = $this->mysql->query($sql);
            return $result->fetch_all(MYSQLI_ASSOC); 
        }

        public function getPostsByUserId($userId) {
            $sql = "SELECT p.id, p.title, p.content, p.type,
                        p.user_id AS userId, 
                        p.votes_count AS votesCount, 
                        p.comments_count AS commentsCount, 
                        p.creation_date AS createdAt, 
                        u.username,
                        u.avatar_url AS avatarUrl
                    FROM posts p 
                    INNER JOIN users u ON p.user_id = u.id 
                    WHERE p.user_id = ? 
                    ORDER BY p.creation_date DESC";
                    
            $query = $this->mysql->prepare($sql);
            $query->bind_param("i", $userId);
            $query->execute();
            
            $result = $query->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }
    }
?>