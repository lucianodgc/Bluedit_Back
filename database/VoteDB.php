<?php
include_once __DIR__ . "/../config/Connect.php";

class VoteDB {
    private $mysql;

    public function __construct() {
        $connect = new Connect();
        $this->mysql = $connect->getConnection();
    }

    public function registerVote($vote) {
        $postId = $vote->getPostId();
        $userId = $vote->getUserId();
        $voteType = $vote->getVoteType();

        $sql = "SELECT post_id FROM votes WHERE post_id = ? AND user_id = ?";
        $query = $this->mysql->prepare($sql);
        $query->bind_param("ii", $postId, $userId);
        $query->execute();
        $result = $query->get_result();
        $alreadyVoted = $result->fetch_assoc();

        if ($voteType == 0) {
            $sql = "DELETE FROM votes WHERE post_id = ? AND user_id = ?";
            $query = $this->mysql->prepare($sql);
            $query->bind_param("ii", $postId, $userId);
        } else if ($alreadyVoted) {
            $sql = "UPDATE votes SET vote_type = ? WHERE post_id = ? AND user_id = ?";
            $query = $this->mysql->prepare($sql);
            $query->bind_param("iii", $voteType, $postId, $userId);
        } else {
            $sql = "INSERT INTO votes (post_id, user_id, vote_type) VALUES (?, ?, ?)";
            $query = $this->mysql->prepare($sql);
            $query->bind_param("iii", $postId, $userId, $voteType);
        }

        if (!$query->execute()) {
            return false;
        }

        $sql = "UPDATE posts SET votes_count = (SELECT IFNULL(SUM(vote_type), 0) FROM votes WHERE post_id = ?) WHERE id = ?";
        $query = $this->mysql->prepare($sql);
        $query->bind_param("ii", $postId, $postId);
        
        return $query->execute();
    }
}
