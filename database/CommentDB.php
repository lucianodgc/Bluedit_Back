<?php
include_once __DIR__ . "/../config/Connect.php";
include_once __DIR__ . "/../models/Comment.php";

class CommentDB {
    private $mysql;

    public function __construct() {
        $connect = new Connect();
        $this->mysql = $connect->getConnection();
    }

    public function create($comment) {
        $this->mysql->begin_transaction();

        try {
            $sql = "INSERT INTO comments (content, user_id, post_id, creation_date) VALUES (?, ?, ?, ?)";
            $query = $this->mysql->prepare($sql);
            
            $content = $comment->getContent();
            $userId  = $comment->getUserId();
            $postId  = $comment->getPostId();
            $date    = $comment->getCreationDate();

            $query->bind_param("siis", $content, $userId, $postId, $date);

            if (!$query->execute()) {
                throw new Exception("Error al insertar el comentario.");
            }

            $newCommentId = $query->insert_id;

            $sqlUpdate = "UPDATE posts SET comments_count = comments_count + 1 WHERE id = ?";
            $updateQuery = $this->mysql->prepare($sqlUpdate);
            $updateQuery->bind_param("i", $postId);
            
            if (!$updateQuery->execute()) {
                throw new Exception("No se pudo incrementar el contador de comentarios del post.");
            }
                
            $this->mysql->commit();
            return $newCommentId;

        } catch (Throwable $e) {
            $this->mysql->rollback();
            throw $e;
        }
    }

    public function getCommentsByPostId($postId) {
        $sql = "SELECT c.id, c.content,
                       c.user_id AS userId,
                       c.post_id AS postId,
                       c.creation_date AS createdAt,
                       u.username,
                       u.avatar_url AS avatarUrl
                FROM comments c
                LEFT JOIN users u ON c.user_id = u.id
                WHERE c.post_id = ?
                ORDER BY c.creation_date ASC";

        $query = $this->mysql->prepare($sql);
        $query->bind_param("i", $postId);
        $query->execute();
        
        $result = $query->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    } 

    public function deleteComment($commentId) {
        $this->mysql->begin_transaction();

        try {
            $sqlFind = "SELECT post_id FROM comments WHERE id = ?";
            $queryFind = $this->mysql->prepare($sqlFind);
            $queryFind->bind_param("i", $commentId);
            $queryFind->execute();
            $resultFind = $queryFind->get_result()->fetch_assoc();

            if (!$resultFind) {
                $this->mysql->rollback();
                return false;
            }

            $postId = $resultFind['post_id'];

            $sqlDelete = "DELETE FROM comments WHERE id = ?";
            $queryDelete = $this->mysql->prepare($sqlDelete);
            $queryDelete->bind_param("i", $commentId);
            $queryDelete->execute();

            if ($queryDelete->affected_rows === 0) {
                $this->mysql->rollback();
                return false;
            }

            $sqlUpdate = "UPDATE posts SET comments_count = GREATEST(0, comments_count - 1) WHERE id = ?";
            $updateQuery = $this->mysql->prepare($sqlUpdate);
            $updateQuery->bind_param("i", $postId);
            $updateQuery->execute();

            $this->mysql->commit();
            return true;

        } catch (Throwable $e) {
            $this->mysql->rollback();
            throw $e;
        }
    }
}