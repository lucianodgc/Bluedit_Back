<?php
    require_once __DIR__ . '/../init.php';

    $postDB = new PostDB();

    $userId = isset($_GET['userId']) ? $_GET['userId'] : null;

    $posts = $postDB->getPostsByVotes($userId);

    Response::sendResponse(200, true, "Posts obtenidos", $posts);