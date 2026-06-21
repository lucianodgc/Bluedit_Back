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

    public function getPosts($currentUserId = null, $searchTerm = null) {
    $sql = "SELECT p.id, p.title, p.content, p.type,
                p.user_id AS userId, 
                p.votes_count AS votesCount, 
                p.comments_count AS commentsCount, 
                p.creation_date AS createdAt, 
                u.username,
                u.avatar_url AS avatarUrl,
                v.vote_type AS userLoggedVote
            FROM posts p 
            LEFT JOIN users u ON p.user_id = u.id 
            LEFT JOIN votes v ON p.id = v.post_id AND v.user_id = ?";
    
    if ($searchTerm) {
        $sql .= " WHERE p.title LIKE ? OR p.content LIKE ?";
    }
    
    $sql .= " ORDER BY p.creation_date DESC";
    
    $query = $this->mysql->prepare($sql);
    $userIdToBind = $currentUserId ?? 0;
    
    if ($searchTerm) {
        $likeTerm = "%" . $searchTerm . "%";
        $query->bind_param("iss", $userIdToBind, $likeTerm, $likeTerm);
    } else {
        $query->bind_param("i", $userIdToBind);
    }
    
    $query->execute();
    $result = $query->get_result();
    return $result->fetch_all(MYSQLI_ASSOC); 
}


    public function getPostById($postId, $currentUserId = null) {
        $sql = "SELECT p.id, p.title, p.content, p.type,
                    p.user_id AS userId, 
                    p.votes_count AS votesCount, 
                    p.comments_count AS commentsCount, 
                    p.creation_date AS createdAt, 
                    u.username,
                    u.avatar_url AS avatarUrl,
                    v.vote_type AS userLoggedVote
                FROM posts p 
                LEFT JOIN users u ON p.user_id = u.id 
                LEFT JOIN votes v ON p.id = v.post_id AND v.user_id = ?
                WHERE p.id = ?";

        $query = $this->mysql->prepare($sql);
        $currentUserIdToBind = $currentUserId ?? 0;
        $query->bind_param("ii", $currentUserIdToBind, $postId);
        $query->execute();
        $result = $query->get_result();
        return $result->fetch_assoc() ?: null;
    }

    public function getPostsByVotes($currentUserId = null) {
        $sql = "SELECT p.id, p.title, p.content, p.type,
                    p.user_id AS userId, 
                    p.votes_count AS votesCount, 
                    p.comments_count AS commentsCount, 
                    p.creation_date AS createdAt, 
                    u.username,
                    u.avatar_url AS avatarUrl,
                    v.vote_type AS userLoggedVote
                FROM posts p 
                LEFT JOIN users u ON p.user_id = u.id 
                LEFT JOIN votes v ON p.id = v.post_id AND v.user_id = ?
                ORDER BY p.votes_count DESC";
                
        $query = $this->mysql->prepare($sql);
        $userIdToBind = $currentUserId ?? 0; 
        $query->bind_param("i", $userIdToBind);
        $query->execute();
        $result = $query->get_result();
        return $result->fetch_all(MYSQLI_ASSOC); 
    }

    public function getPostsByUserId($userId, $currentUserId = null) {
        $sql = "SELECT p.id, p.title, p.content, p.type,
                    p.user_id AS userId, 
                    p.votes_count AS votesCount, 
                    p.comments_count AS commentsCount, 
                    p.creation_date AS createdAt, 
                    u.username,
                    u.avatar_url AS avatarUrl,
                    v.vote_type AS userLoggedVote
                FROM posts p 
                LEFT JOIN users u ON p.user_id = u.id 
                LEFT JOIN votes v ON p.id = v.post_id AND v.user_id = ?
                WHERE p.user_id = ? 
                ORDER BY p.creation_date DESC";

        $query = $this->mysql->prepare($sql);
        $currentUserIdToBind = $currentUserId ?? 0;
        $query->bind_param("ii", $currentUserIdToBind, $userId);
        $query->execute();
        $result = $query->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function deletePost($postId) {
        $sql = "DELETE FROM posts WHERE id = ?";
        $query = $this->mysql->prepare($sql);
        $query->bind_param('i', $postId);
        $query->execute();
        
        return $query->affected_rows > 0;
    }
}