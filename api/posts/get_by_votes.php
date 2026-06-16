<?php
    require_once __DIR__ . '/../init.php';

    $userId = isset($_GET['userId']) ? $_GET['userId'] : null;

    $postDB = new PostDB();

    $posts = $postDB->getPostsByVotes($userId);

    Response::sendResponse(200, true, "Posts obtenidos", $posts);