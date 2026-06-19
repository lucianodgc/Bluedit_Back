<?php
include_once __DIR__ . "/../config/Connect.php";
include_once __DIR__ . "/../models/Vote.php";

class VoteDB {
    private $mysql;

    public function __construct() {
        $connect = new Connect();
        $this->mysql = $connect->getConnection();
    }

    public function registerVote($vote) {
        $postId   = $vote->getPostId();
        $userId   = $vote->getUserId();
        $voteType = $vote->getVoteType();

        $this->mysql->begin_transaction();

        try {
            $sql = "SELECT post_id FROM votes WHERE post_id = ? AND user_id = ?";
            $query = $this->mysql->prepare($sql);
            $query->bind_param("ii", $postId, $userId);
            $query->execute();
            $result = $query->get_result();
            $alreadyVoted = $result->fetch_assoc();

            if ($voteType === 0) {
                $sqlAction = "DELETE FROM votes WHERE post_id = ? AND user_id = ?";
                $queryAction = $this->mysql->prepare($sqlAction);
                $queryAction->bind_param("ii", $postId, $userId);
            } else if ($alreadyVoted) {
                $sqlAction = "UPDATE votes SET vote_type = ? WHERE post_id = ? AND user_id = ?";
                $queryAction = $this->mysql->prepare($sqlAction);
                $queryAction->bind_param("iii", $voteType, $postId, $userId);
            } else {
                $sqlAction = "INSERT INTO votes (post_id, user_id, vote_type) VALUES (?, ?, ?)";
                $queryAction = $this->mysql->prepare($sqlAction);
                $queryAction->bind_param("iii", $postId, $userId, $voteType);
            }

            if (!$queryAction->execute()) {
                throw new Exception("Error al ejecutar la acción de votación.");
            }

            $sqlUpdate = "UPDATE posts SET votes_count = (SELECT IFNULL(SUM(vote_type), 0) FROM votes WHERE post_id = ?) WHERE id = ?";
            $queryUpdate = $this->mysql->prepare($sqlUpdate);
            $queryUpdate->bind_param("ii", $postId, $postId);
            
            if (!$queryUpdate->execute()) {
                throw new Exception("Error al actualizar el contador consolidado en la tabla posts.");
            }

            $this->mysql->commit();
            return true;

        } catch (Throwable $e) {
            $this->mysql->rollback();
            throw $e;
        }
    }
}